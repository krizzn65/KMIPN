# AquaMonitor Minimalist UI Design System

## Overview

This design system implements a clean, minimalist, and elegant user interface for the AquaMonitor water quality monitoring application. The design emphasizes centered layouts, generous whitespace, consistent typography, and a refined professional color palette.

## Color Palette

### Primary Colors

-   **Primary**: `#2C3E50` (Deep charcoal blue)
-   **Secondary**: `#95A5A6` (Soft gray)
-   **Accent**: `#3498DB` (Subtle blue)
-   **Background**: `#F8F9FA` (Very light gray)
-   **Surface**: `#FFFFFF` (White)

### Status Colors

-   **Success**: `#27AE60` (Green)
-   **Warning**: `#F39C12` (Orange)
-   **Danger**: `#E74C3C` (Red)

### Text Colors

-   **Primary Text**: `#2C3E50`
-   **Secondary Text**: `#7F8C8D`
-   **Border**: `#ECF0F1`

## Typography

### Font Family

-   **Primary**: 'Inter', sans-serif
-   **Fallback**: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif

### Font Weights

-   **Light**: 300
-   **Regular**: 400 (default)
-   **Medium**: 500
-   **Semi-bold**: 600

### Font Sizes

-   **h1**: 2.5rem (40px)
-   **h2**: 2rem (32px)
-   **h3**: 1.75rem (28px)
-   **h4**: 1.5rem (24px)
-   **h5**: 1.25rem (20px)
-   **h6**: 1rem (16px)
-   **Body**: 1rem (16px)

## Spacing System

All spacing uses an 8px base unit:

-   **xs**: 4px
-   **sm**: 8px
-   **md**: 16px
-   **lg**: 24px
-   **xl**: 32px
-   **2xl**: 48px
-   **3xl**: 64px

## Border Radius

-   **sm**: 4px
-   **md**: 8px
-   **lg**: 12px
-   **xl**: 16px

## Shadows

-   **sm**: `0 1px 3px rgba(0, 0, 0, 0.1)`
-   **md**: `0 4px 6px rgba(0, 0, 0, 0.1)`
-   **lg**: `0 10px 15px rgba(0, 0, 0, 0.1)`
-   **xl**: `0 20px 25px rgba(0, 0, 0, 0.1)`

## Components

### Cards

```css
.card {
    background: var(--surface-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: var(--space-xl);
    border: 1px solid var(--border-color);
}
```

### Buttons

```css
.btn {
    padding: var(--space-md) var(--space-lg);
    border-radius: var(--border-radius-md);
    font-weight: var(--font-weight-medium);
}
```

### Form Elements

```css
.form-control {
    padding: var(--space-md);
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-md);
}
```

## Layout Principles

1. **Centered Content**: All primary content is horizontally centered
2. **Generous Whitespace**: Ample spacing between elements for clarity
3. **Visual Hierarchy**: Clear typographic hierarchy and component grouping
4. **Consistent Spacing**: 8px-based spacing system throughout
5. **Subtle Depth**: Minimal shadows for gentle depth perception

## Responsive Design

### Breakpoints

-   **Mobile**: < 768px
-   **Tablet**: 768px - 1024px
-   **Desktop**: > 1024px

### Mobile Adaptations

-   Reduced padding and spacing
-   Stacked layouts for cards and forms
-   Simplified navigation
-   Touch-friendly button sizes

## Animations

### Fade In

```css
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

### Slide In

```css
@keyframes slideIn {
    from {
        transform: translateX(-20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
```

## Usage

### Including the Design System

```html
<link rel="stylesheet" href="{{ asset('css/minimalist-ui.css') }}" />
```

### Utility Classes

-   `.text-center` - Center align text
-   `.flex-center` - Flexbox centering
-   `.container-centered` - Centered container with max-width
-   `.mb-1` to `.mb-5` - Margin bottom utilities
-   `.mt-1` to `.mt-5` - Margin top utilities
-   `.p-1` to `.p-5` - Padding utilities

## Browser Support

-   Chrome 60+
-   Firefox 60+
-   Safari 12+
-   Edge 79+
-   iOS Safari 12+
-   Android Chrome 60+

## Accessibility

-   WCAG 2.1 AA compliant
-   High contrast ratios (4.5:1 minimum)
-   Keyboard navigation support
-   Screen reader friendly
-   Focus indicators for interactive elements

## File Structure

```
public/
└── css/
    └── minimalist-ui.css    # Main design system CSS

resources/views/
├── auth/
│   └── login.blade.php     # Redesigned login page
└── dashboard.blade.php     # Redesigned dashboard page
```

## Customization

To customize the design system, modify the CSS variables in `minimalist-ui.css`:

```css
:root {
    --primary-color: #your-color;
    --font-family: "Your-Font", sans-serif;
    /* ... other variables */
}
```

## Best Practices

1. Use semantic HTML structure
2. Maintain consistent spacing
3. Follow the color hierarchy
4. Use utility classes for layout
5. Test across different screen sizes
6. Ensure accessibility compliance
