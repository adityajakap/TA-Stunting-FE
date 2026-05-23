# 📏 Responsive Breakpoints Visual Guide

## Breakpoints yang Digunakan

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         DEVICE SIZES & BREAKPOINTS                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  📱 MOBILE (XS)          📱 TABLET (SM/MD)        🖥️ DESKTOP (LG)           │
│  320px - 575px           576px - 991px            992px+                    │
│  ┌──────────────┐         ┌──────────────┐        ┌────────────────────┐   │
│  │              │         │              │        │                    │   │
│  │  Single      │         │  2-column    │        │  Full Layout       │   │
│  │  Column      │         │  Layout      │        │  Max-width: 1280px │   │
│  │              │         │              │        │                    │   │
│  │  Padding:    │         │  Padding:    │        │  Padding: 2rem-5%  │   │
│  │  0.5-1rem    │         │  1-2rem      │        │                    │   │
│  │              │         │              │        │  Font: 1rem        │   │
│  │  Font:       │         │  Font:       │        │  Line-height: 1.6  │   │
│  │  0.85-0.9rem │         │  0.9-0.95rem │        │                    │   │
│  └──────────────┘         └──────────────┘        └────────────────────┘   │
│                                                                              │
│  ✓ iPhone SE               ✓ iPad Mini              ✓ Desktop 1920px        │
│  ✓ iPhone 12 max           ✓ Galaxy Tab            ✓ Laptop 1366px         │
│  ✓ Samsung S10             ✓ iPad Air              ✓ Monitor 2560px         │
│  ✓ Pixel 5                 ✓ iPad Pro              ✓ TV Screen              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

## Layout Transformation Examples

### 1️⃣ Hero Section
```
📱 MOBILE                  📱 TABLET                🖥️ DESKTOP
(Portrait)                 (Landscape)              (Full Width)

┌──────────────┐          ┌────────────────────┐   ┌─────────────────────┐
│              │          │   TEXT    │ IMAGE  │   │   TEXT       │IMAGE │
│ ┌──────────┐ │          │           │        │   │              │      │
│ │  TEXT    │ │          │           │        │   │              │      │
│ │          │ │          │           │        │   │              │      │
│ └──────────┘ │          │           │        │   │              │      │
│              │          │           │        │   │              │      │
│ ┌──────────┐ │          └────────────────────┘   └─────────────────────┘
│ │  IMAGE   │ │
│ │          │ │
│ │          │ │
│ └──────────┘ │
│              │
└──────────────┘

Column Layout          2-Column Layout         Horizontal Layout
Image: 100%            Image: 45%               Image: 40%
Font: 1.3rem          Font: 1.5rem             Font: 2rem
```

### 2️⃣ Feature Grid
```
📱 MOBILE              📱 TABLET              🖥️ DESKTOP
┌────────┐             ┌────────┬────────┐   ┌──┬──┬──┬──┐
│ CARD 1 │             │ CARD 1 │ CARD 2 │   │C1│C2│C3│C4│
├────────┤             ├────────┼────────┤   ├──┼──┼──┼──┤
│ CARD 2 │             │ CARD 3 │ CARD 4 │   │C5│C6│C7│C8│
├────────┤             └────────┴────────┘   └──┴──┴──┴──┘
│ CARD 3 │
├────────┤             1fr 1fr              1fr 1fr 1fr 1fr
│ CARD 4 │             (2 cols)             (4 cols)
└────────┘
   1fr
(1 col)
```

### 3️⃣ Navigation Bar
```
📱 MOBILE              📱 TABLET              🖥️ DESKTOP

┌──────────────────┐   ┌────────────────────┐ ┌─────────────────────────┐
│ LOGO │ BURGER 🍔 │   │LOGO│HOME│MENU│LINKS│ │LOGO│HOME│MENU│LINKS│USR│
└──────────────────┘   └────────────────────┘ └─────────────────────────┘

Height: 64px           Height: 68px           Height: 72px
Padding: 0.5rem        Padding: 1rem          Padding: 0.7rem
Font: 0.85rem          Font: 0.9rem           Font: 1rem
Gap: 0.25rem           Gap: 0.5rem            Gap: 1rem
```

### 4️⃣ Form Buttons
```
📱 MOBILE              📱 TABLET              🖥️ DESKTOP

┌─────────────────┐   ┌─────────────┬──────┐ ┌─────────┬────────┐
│   SIMPAN UBAH   │   │ SIMPAN │    │ UBAH │ │ SIMPAN │ UBAH   │
├─────────────────┤   └─────────────┴──────┘ └─────────┴────────┘
│   BATAL HAPUS   │
└─────────────────┘

Stack (100% width)    Flex (wrap at 2)    Flex (no wrap)
Button width: 100%    Button width: 48%   Button width: auto
Gap: 0.5rem           Gap: 0.75rem        Gap: 1rem
```

## CSS Media Query Syntax

```css
/* Mobile First Approach */
.element {
  /* Base styles untuk mobile */
  width: 100%;
  padding: 1rem;
  font-size: 0.9rem;
}

/* Tablet & Up */
@media (min-width: 576px) {
  .element {
    width: 50%;
    padding: 1.5rem;
    font-size: 0.95rem;
  }
}

/* Medium & Up */
@media (min-width: 768px) {
  .element {
    width: 33.33%;
    padding: 2rem;
    font-size: 1rem;
  }
}

/* Desktop & Up */
@media (min-width: 992px) {
  .element {
    width: 25%;
    padding: 2rem;
    font-size: 1rem;
  }
}
```

## Responsive Image Sizing

```
📱 Mobile            📱 Tablet            🖥️ Desktop
150px               200px                 350px
(40% padding)       (35% padding)          (30% padding)
   ↓                  ↓                       ↓
┌──────────┐        ┌────────────┐       ┌────────────────┐
│          │        │            │       │                │
│  IMAGE   │        │   IMAGE    │       │     IMAGE      │
│          │        │            │       │                │
└──────────┘        └────────────┘       └────────────────┘

Scaling: Linear (proportional)
Aspect Ratio: Maintained (object-fit: cover/contain)
```

## Font Sizing Scale

```
           MOBILE    TABLET    DESKTOP   
           (xs)      (sm/md)   (lg+)
           ────      ───────   ───────

h1         1.5rem    1.8rem    2.5rem    ▲
h2         1.3rem    1.5rem    2rem      │ Larger
h3         1.1rem    1.3rem    1.5rem    │
h4         1rem      1.1rem    1.2rem    │
p/body     0.95rem   0.95rem   1rem      │
small      0.85rem   0.9rem    0.95rem   ▼ Smaller

                     Grows Progressively
```

## Spacing Scale

```
               MOBILE   TABLET   DESKTOP
               ──────   ──────   ──────

Container
Padding        0.5rem   1rem     2rem

Card
Padding        1rem     1.5rem   2rem
Gap            0.75rem  1rem     1.5rem

Form
mb-3           1.2rem   1.5rem   1.5rem
Input Padding  0.6rem   0.7rem   0.75rem

Button
Padding        0.5rem   0.6rem   0.75rem
             1rem      1.2rem   1.5rem
```

## Touch Target Sizes

```
Recommended minimum: 44x44px for mobile

┌────────────────────────────┐
│                            │
│      44px × 44px           │
│    Minimum Touch Size       │
│      (Safe Zone)           │
│                            │
└────────────────────────────┘

✅ Good spacing for:
   - Buttons
   - Links
   - Form inputs
   - Select elements

❌ Too small (<30px):
   - Hard to tap
   - Higher error rate
   - Poor accessibility
```

## Grid Column Examples

```
MOBILE (1 column)
┌────────────────────┐
│   ITEM 1           │
├────────────────────┤
│   ITEM 2           │
├────────────────────┤
│   ITEM 3           │
├────────────────────┤
│   ITEM 4           │
└────────────────────┘

TABLET (2 columns)
┌──────────────┬──────────────┐
│   ITEM 1     │   ITEM 2     │
├──────────────┼──────────────┤
│   ITEM 3     │   ITEM 4     │
└──────────────┴──────────────┘

DESKTOP (4 columns)
┌────┬────┬────┬────┐
│ I1 │ I2 │ I3 │ I4 │
├────┼────┼────┼────┤
│ I5 │ I6 │ I7 │ I8 │
└────┴────┴────┴────┘
```

## Color & Contrast

```
Text Color        Background      Ratio    WCAG
──────────        ──────────      ─────    ─────
#005f77 (Teal)    #ffffff (White) 5.2:1    AA ✓
#ffffff (White)   #005f77 (Teal)  5.2:1    AA ✓
#333333 (Dark)    #f8f9fa (Light) 12:1     AAA ✓
#6b7280 (Gray)    #ffffff (White) 4.5:1    AA ✓
```

## Device Width Reference

```
Device              Width    Breakpoint    Safe Area*
────────            ─────    ──────────    ──────────
iPhone SE           375px    xs            355px
iPhone 12           390px    xs            370px
iPhone 12 Pro Max   428px    xs            408px
Samsung S10         360px    xs            340px
iPad Mini           768px    md            748px
iPad Air            820px    md            800px
iPad Pro 11"        834px    md            814px
MacBook Air 13"     1440px   lg            1400px
Desktop 27"         2560px   lg            2520px
```
*Safe area = usable width after accounting for browser chrome

## Loading & Performance

```
File Size Impact:

Mobile (1x)      Tablet (1.5x)    Desktop (2x)
50KB CSS         60KB CSS         70KB CSS
100KB IMG        150KB IMG        250KB IMG

Load Time (3G):
Mobile:   ~2-3 seconds
Tablet:   ~1-2 seconds
Desktop:  <1 second

Strategy: Serve appropriate image sizes per breakpoint
```

---

**Visual Guide Version:** 1.0  
**Last Updated:** May 23, 2026  
**Reference:** TA-Stunting Responsive Design
