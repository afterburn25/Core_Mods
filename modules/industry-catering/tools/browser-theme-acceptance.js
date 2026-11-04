'use strict';

// Browser-level acceptance for the real HTML rendered from the selected
// Catering 2.1.2 PHP templates, without touching the hosted demo.
const { chromium } = require('playwright');

const baseUrl = process.env.CATERING_PREVIEW_URL;
const outputDirectory = process.env.CATERING_PREVIEW_OUTPUT;
if (!baseUrl || !outputDirectory) {
  throw new Error('Set CATERING_PREVIEW_URL and CATERING_PREVIEW_OUTPUT.');
}

const heroSelector = {
  'heritage-editorial': '.te-visual',
  'modern-culinary': '.tm-intro',
  'garden-celebration': '.tg-hero',
  'grand-wedding': '.tw-hero',
  'corporate-table': '.tc-banner',
  'rustic-gathering': '.tr-collage',
  'bayou-night': '.tb-hero',
  'market-kitchen': '.tk-market',
  'social-feast': '.ts-mosaic',
  'minimal-luxe': '.tl-opening',
};
const pages = ['index', 'services', 'menus', 'packages', 'about', 'gallery', 'faq', 'contact'];
const viewports = [
  { name: 'desktop', width: 1440, height: 900 },
  { name: 'mobile', width: 390, height: 844 },
];
const legacySelectors = {
  index: '.c-hero-home', services: '.c-page-hero-services',
  menus: '.c-page-hero-menu', packages: '.c-page-hero-packages',
  about: '.c-page-hero-about', gallery: '.c-page-title-centered',
  faq: '.c-page-title-centered',
};

(async () => {
  const browser = await chromium.launch({ headless: true, args: ['--no-sandbox'] });
  const results = [];
  const failures = [];
  try {
    for (const viewport of viewports) {
      const context = await browser.newContext({ viewport, deviceScaleFactor: 1 });
      try {
        for (const [theme, selector] of Object.entries(heroSelector)) {
          const mediaSeen = new Set();
          for (const route of pages) {
            const page = await context.newPage();
            const errors = [];
            const failedLocalResources = [];
            page.on('pageerror', (error) => errors.push(error.message));
            page.on('response', (response) => {
              if (response.status() >= 400 && response.url().startsWith(baseUrl)) {
                failedLocalResources.push(response.status() + ' ' + response.url());
              }
            });
            try {
              const response = await page.goto(
                baseUrl.replace(/\/$/, '') + '/' + theme + '/' + route + '.html',
                { waitUntil: 'load', timeout: 25000 },
              );
              if (!response || response.status() !== 200) {
                throw new Error('Preview HTML did not return HTTP 200');
              }
              await page.evaluate(() => document.fonts && document.fonts.ready);
              const report = await page.evaluate((input) => {
                const header = document.querySelector('[data-site-header]');
                const nav = document.querySelector('[data-nav]');
                const hero = document.querySelector(input.selector);
                const headerBox = header?.getBoundingClientRect();
                const navBox = nav?.getBoundingClientRect();
                const heroBox = hero?.getBoundingClientRect();
                const image = hero?.querySelector('img');
                const background = hero ? getComputedStyle(hero).backgroundImage : '';
                return {
                  h1: document.querySelectorAll('h1').length,
                  legacyHeroPresent: Boolean(input.legacy && document.querySelector(input.legacy)),
                  headerHeight: headerBox ? Math.round(headerBox.height) : null,
                  headerTop: headerBox ? Math.round(headerBox.top) : null,
                  headerPosition: header ? getComputedStyle(header).position : null,
                  navLinks: nav ? nav.querySelectorAll('a').length : 0,
                  navCenterDeviation: navBox && getComputedStyle(nav).display !== 'none'
                    ? Math.round(Math.abs(navBox.left + navBox.width / 2 - innerWidth / 2)) : null,
                  bodyWidth: document.body.scrollWidth,
                  viewportWidth: innerWidth,
                  heroHeight: heroBox ? Math.round(heroBox.height) : null,
                  heroMedia: image?.src || background,
                  heroImageLoaded: image ? image.complete && image.naturalWidth > 0
                    : background.includes('url('),
                  heroImageCount: hero ? hero.querySelectorAll('img').length
                    + (background.includes('url(') ? 1 : 0) : 0,
                  navDisplay: nav ? getComputedStyle(nav).display : null,
                };
              }, { selector, legacy: legacySelectors[route] || null });

              if (report.heroMedia && mediaSeen.has(report.heroMedia)) {
                failures.push(theme + ' ' + route + ' repeats another route hero image');
              }
              if (report.heroMedia) mediaSeen.add(report.heroMedia);

              const fullHeight = await page.evaluate(() => document.documentElement.scrollHeight);
              if (fullHeight > viewport.height + 150) {
                await page.evaluate(() => window.scrollTo(0, Math.min(700,
                  document.documentElement.scrollHeight - innerHeight)));
                // Scroll layout is frame-driven; measuring in the same tick
                // produces false sticky-header failures on announcement bars.
                await page.waitForTimeout(100);
                report.stickyTop = await page.locator('[data-site-header]').evaluate((el) =>
                  Math.round(el.getBoundingClientRect().top));
              } else {
                report.stickyTop = null;
              }

              if (viewport.name === 'mobile' && route === 'index') {
                await page.evaluate(() => scrollTo(0, 0));
                const toggle = page.locator('[data-nav-toggle]');
                await toggle.click();
                report.mobileOpened = await page.locator('[data-nav]').evaluate((el) =>
                  getComputedStyle(el).display !== 'none');
                report.mobileExpanded = await toggle.getAttribute('aria-expanded');
                await page.keyboard.press('Escape');
                report.mobileClosed = (await toggle.getAttribute('aria-expanded')) === 'false';
              }

              if (report.h1 !== 1 || report.legacyHeroPresent
                || report.headerPosition !== 'sticky'
                || (theme === 'market-kitchen'
                  ? report.headerTop < 0 || report.headerTop > 60
                  : report.headerTop !== 0)
                || (report.stickyTop !== null && report.stickyTop !== 0)
                || report.headerHeight > (viewport.name === 'desktop' ? 62 : 60)
                || report.navLinks < 4 || !report.heroImageLoaded
                || report.heroImageCount !== 1 || !report.heroHeight
                || report.heroHeight > (viewport.name === 'desktop' ? 680 : 650)
                || report.bodyWidth > report.viewportWidth + 1
                || (viewport.name === 'desktop' && report.navCenterDeviation > 30)
                || (viewport.name === 'mobile' && route === 'index'
                  && (!report.mobileOpened || report.mobileExpanded !== 'true'
                    || !report.mobileClosed))
                || errors.length > 0 || failedLocalResources.length > 0) {
                failures.push({ theme, route, viewport: viewport.name, ...report,
                  errors, failedLocalResources });
              }

              if (route === 'index' || route === 'menus' || route === 'about') {
                await page.evaluate(() => scrollTo(0, 0));
                await page.screenshot({
                  path: outputDirectory + '/' + theme + '-' + route + '-' + viewport.name + '.png',
                  fullPage: false,
                });
              }
              results.push({ theme, route, viewport: viewport.name, ...report });
            } catch (error) {
              failures.push({ theme, route, viewport: viewport.name, error: error.message });
            } finally {
              await page.close();
            }
          }
        }
      } finally {
        await context.close();
      }
    }
  } finally {
    await browser.close();
  }
  console.log(JSON.stringify({
    checked: results.length, themes: Object.keys(heroSelector).length,
    routesPerTheme: pages.length, viewports: viewports.length,
    failureCount: failures.length, failures,
  }, null, 2));
  if (failures.length) process.exitCode = 1;
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
