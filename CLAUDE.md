# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Static HTML/CSS/JS portfolio website for "Sawa IT Create Partner" — a small IT consulting business. No build tools, no npm, no frameworks. Files deploy directly to Xserver hosting.

## Development

```bash
# Local dev server (no build step needed)
python3 -m http.server 8000
```

Open `http://localhost:8000` in a browser. PHP contact form (`contact.php`) requires a PHP-capable server (Xserver in production).

## Architecture

**Pages:** `index.html`, `about.html`, `works.html`, `price.html`, `contact.html`, `contact.php`

All pages share one stylesheet (`css/styles.css`) and one script (`js/script.js`). No routing — standard multi-page static site.

### CSS (styles.css ~2,650 lines)

- **BEM naming**: `.block__element--modifier` (e.g. `.work-card__image--screenshot`)
- **CSS custom properties** on `:root` for colors, fonts, transitions
- **Responsive breakpoints**: mobile-first, 768px (tablet), 1024px (desktop), 1100px (max font)
- **Liquid typography**: font-size scales via `vw` units between 375px–1100px

### Theme System

5 themes controlled by `data-theme` attribute on `<html>`:
- `natural` (default), `glass`, `botanical`, `scandinavian`, `retro`
- Each theme overrides CSS variables via `[data-theme="name"]` selectors
- Persisted in `localStorage` key `"sawaTheme"`
- FOUC prevention inline script in each page's `<head>` reads localStorage before render

### JavaScript (script.js ~170 lines, vanilla)

- Hamburger menu toggle (`.c-hamburger`)
- Scroll-to-top button visibility (threshold: 200px)
- Smooth scroll for anchor links with fixed-header offset
- Header transparency toggle on scroll (threshold: 80px)
- `IntersectionObserver` adds `.is-visible` to `.fade-in` elements (threshold: 0.15)
- Dynamic iframe scaling for work card previews (1280px source → container width)
- Hero star generation (60 random stars with CSS custom property animation timing)

### Work Cards

Two display modes for portfolio items:
- **iframe preview**: Live website embed scaled down (used for web projects)
- **screenshot image**: Static image via `.work-card__image--screenshot` + `.work-card__screenshot-img` (used for app projects)
- **logo display**: SVG/image via `.work-card__image--logo` (used for design work)

### Contact Form (contact.php)

POST handler using `mb_send_mail()`. Sends to `sawa.designers.office@gmail.com` from `info@sawa-works-design.com` (Xserver mail). Includes input sanitization and header injection prevention.

## Conventions

- Fonts: "Zen Maru Gothic" (Google Fonts) for body, "madre-script" (Adobe Fonts) for decorative headings
- Accent color: `#c69c6d` (warm brown) in natural theme
- The `.name-it` class applies italic to the "IT" in brand name
- All scroll-animated elements use the `.fade-in` class
- Works page uses `.works__category` headings to separate "Web Design" and "App Development" sections
