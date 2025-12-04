# Attendance Module - Theme & Styling Guide

## Overview

The Attendance Module uses the Calendar Module's modern styling approach, which relies on CSS custom properties (CSS variables) for theming and inline styles for component-specific styling.

## CSS Variables Used

The module expects the following CSS variables to be defined in your main layout:

### Spacing Variables
```css
--space-xs: 0.25rem;      /* 4px */
--space-sm: 0.5rem;       /* 8px */
--space-md: 1rem;         /* 16px */
--space-lg: 1.5rem;       /* 24px */
--space-xl: 2rem;         /* 32px */
```

### Text Variables
```css
--text-primary: #1f2937;      /* Main text color */
--text-secondary: #6b7280;    /* Secondary text */
--text-muted: #9ca3af;        /* Muted text */
```

### Font Variables
```css
--font-xs: 0.75rem;       /* 12px */
--font-sm: 0.875rem;      /* 14px */
--font-md: 1rem;          /* 16px */
--font-lg: 1.25rem;       /* 20px */
```

### Card & Border Variables
```css
--card-border: #e5e7eb;   /* Card border color */
--radius-md: 0.375rem;    /* Border radius */
```

### Status Colors
```css
--success: #10b981;       /* Present, Completed */
--danger: #ef4444;        /* Absent, Cancelled */
--warning: #f59e0b;       /* Late, Pending */
--info: #3b82f6;          /* Half-Day */
--secondary: #6b7280;     /* On Leave */
```

### Button Classes
```css
.btn                      /* Base button style */
.btn-primary              /* Primary button */
.btn-light                /* Light button */
.btn-danger               /* Danger button */
.btn-xs                   /* Extra small button */
.btn-sm                   /* Small button */
```

### Form Classes
```css
.form-label               /* Form label */
.form-control             /* Input, textarea, select */
```

### Badge Classes
```css
.badge                    /* Base badge */
.badge-success            /* Success badge */
.badge-danger             /* Danger badge */
.badge-warning            /* Warning badge */
.badge-info               /* Info badge */
.badge-secondary          /* Secondary badge */
.badge-dot                /* Small dot indicator */
```

### Card Classes
```css
.card                     /* Card container */
.card-header              /* Card header */
.card-body                /* Card content */
.card-title               /* Card title */
.page-title               /* Page heading */
```

## Inline Style Structure

The module uses inline styles for component-specific styling. Here's the pattern:

### Header/Navigation
```blade
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
    <div>
        <h1 class="page-title">Title</h1>
        <p style="color: var(--text-secondary); font-size: var(--font-sm);">Subtitle</p>
    </div>
</div>
```

### Filter Cards
```blade
<div class="card" style="margin-bottom: var(--space-lg);">
    <div class="card-body" style="display: flex; gap: var(--space-md); flex-wrap: wrap;">
        <!-- Filters here -->
    </div>
</div>
```

### Tables
```blade
<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="border-bottom: 1px solid var(--card-border);">
            <th style="padding: 12px 16px; text-align: left; font-size: var(--font-sm); font-weight: 600; color: var(--text-secondary);">Column</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border-bottom: 1px solid var(--card-border);">
            <td style="padding: 12px 16px;">Content</td>
        </tr>
    </tbody>
</table>
```

### Forms
```blade
<div style="display: grid; gap: var(--space-lg);">
    <div>
        <label class="form-label">Label</label>
        <input type="text" class="form-control">
    </div>
</div>
```

### Status Badges
```blade
<span class="badge badge-{{ $attendance->status_color }}">
    <span class="badge-dot"></span>
    {{ $status }}
</span>
```

## Customization Guide

### Change Primary Colors

Add these CSS variables to your main layout:

```css
:root {
    --text-primary: #your-color;
    --text-secondary: #your-color;
    --text-muted: #your-color;
    --card-border: #your-color;
}
```

### Dark Mode Support

Add these variables for dark mode:

```css
@media (prefers-color-scheme: dark) {
    :root {
        --text-primary: #f3f4f6;
        --text-secondary: #d1d5db;
        --text-muted: #9ca3af;
        --card-border: #374151;
    }
}
```

### Responsive Grid Adjustments

The form grids use `grid-template-columns: 1fr 1fr` by default. Modify in views:

```blade
<!-- Single column -->
<div style="display: grid; gap: var(--space-lg);">

<!-- Two columns (default) -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">

<!-- Three columns -->
<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: var(--space-lg);">

<!-- Responsive -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
```

### Space Adjustments

Change spacing throughout by modifying CSS variables:

```css
:root {
    --space-lg: 2rem;    /* Increase */
    --space-lg: 1rem;    /* Decrease */
}
```

### Font Size Changes

```css
:root {
    --font-sm: 1rem;     /* Make labels larger */
    --font-xs: 0.875rem; /* Smaller secondary text */
}
```

## Component Styling Reference

### Status Color Mapping

```php
'present' => 'success',    /* Green */
'absent' => 'danger',      /* Red */
'late' => 'warning',       /* Orange/Yellow */
'half-day' => 'info',      /* Blue */
'on-leave' => 'secondary', /* Gray */
```

### Empty State Styling

```blade
<div style="padding: 40px; text-align: center; color: var(--text-muted);">
    <svg><!-- Icon --></svg>
    <p>No records found</p>
</div>
```

### Modal Dialogs

```blade
<div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1200;">
    <div class="card" style="width: 400px; max-width: 90%;">
        <!-- Modal content -->
    </div>
</div>
```

## Accessibility Considerations

1. **Color Contrast**: Ensure text colors meet WCAG AA standards (4.5:1 ratio)
2. **Font Sizes**: Keep `--font-sm` at least 12px for readability
3. **Padding**: Maintain consistent padding for touch targets
4. **Semantic HTML**: Use proper form labels and heading hierarchy
5. **ARIA Labels**: Add ARIA labels for interactive elements

## Print Styles

Add to your main stylesheet:

```css
@media print {
    .btn, .nav, .card-header {
        display: none;
    }
    
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid #000;
        padding: 8px;
    }
}
```

## Common Customizations

### Remove Table Borders
```blade
<table style="width: 100%; border-collapse: collapse;">
    <!-- Remove: border-bottom: 1px solid var(--card-border) -->
</table>
```

### Add Hover Effects
```blade
<tr style="border-bottom: 1px solid var(--card-border); transition: background 0.15s;">
    <!-- content -->
</tr>
```

### Change Button Styles
Override button classes in your stylesheet:

```css
.btn-primary {
    background-color: #your-color;
    color: white;
    padding: 10px 16px;
    border-radius: var(--radius-md);
    /* etc */
}
```

### Full Width Forms
```blade
<div style="display: grid; gap: var(--space-lg);">
    <!-- All fields will be full width -->
</div>
```

## Performance Tips

1. **Inline Styles**: Keep inline styles minimal
2. **CSS Classes**: Use classes for frequently reused styles
3. **CSS Variables**: Leverage CSS variables for theme consistency
4. **Media Queries**: Use responsive design patterns for mobile

## Browser Support

The module uses modern CSS features:
- CSS Grid
- CSS Flexbox
- CSS Variables
- CSS Transitions

Supported browsers:
- Chrome 49+
- Firefox 31+
- Safari 9.1+
- Edge 15+
- Mobile browsers (iOS Safari 9.3+, Chrome Android)

## Theme Integration with Other Modules

To maintain consistency across your application:

1. Define all CSS variables in your main layout
2. Use the same color palette throughout
3. Keep spacing values consistent
4. Follow the same responsive breakpoints

Example main layout definition:

```blade
<style>
    :root {
        /* Colors */
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        
        /* Spacing */
        --space-xs: 0.25rem;
        --space-sm: 0.5rem;
        --space-md: 1rem;
        --space-lg: 1.5rem;
        --space-xl: 2rem;
        
        /* Other variables... */
    }
</style>
```

## Support

For theme customization questions, please refer to the module documentation or contact your development team.
