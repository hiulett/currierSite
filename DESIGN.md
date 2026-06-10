# Design System: LogySaas v2
**Project ID:** LogySaas-Logistics-Platform

## 1. Visual Theme & Atmosphere
The LogySaas aesthetic is "Technical Elegance." It combines the robustness of an industrial logistics tool with the clean, airy, and high-performance feel of modern developer tools (like Linear, Stripe, or Vercel). The atmosphere is **Utilitarian yet Premium**, using deep space slates and high-contrast accents to guide the eye toward critical operational data.

## 2. Color Palette & Roles

### Base Neutral Tones (Slate Theme)
*   **Deep Space Slate (#0f172a):** Primary Canvas background. Provides a focused, dark-mode-first environment.
*   **Surface Navy-Slate (#1e293b):** Card Backgrounds. Used to group related data and create physical layers.
*   **Border Steel (#334155):** Structural lines. Used for 1px borders to define components without adding visual weight.
*   **Ghost Slate (#94a3b8):** Secondary Text. Used for labels, timestamps, and metadata.
*   **High-Contrast Cloud (#f1f5f9):** Primary Text/Headings. Ensures maximum readability on dark surfaces.

### Functional Accents
*   **Logi-Blue (#3b7ddd):** Primary Action Color. Used for main buttons, active navigation, and key interactions.
*   **Success Emerald (#10b981):** "Delivered" / "Paid" status. Represents completion and positive outcomes.
*   **Warning Amber (#f59e0b):** "In-Transit" / "Pending" status. Used for items requiring attention but not in crisis.
*   **Danger Ruby (#ef4444):** "Delayed" / "Overdue" status. Used for critical errors or financial alerts.

## 3. Typography Rules
*   **Primary Font:** *Plus Jakarta Sans* or *Inter*.
*   **Headers:** Bold (700 weight) with tight tracking (-0.025em) to feel technical and modern.
*   **Body:** Regular (400 weight) with a size range of 13px-15px for data-dense tables.
*   **Data Points:** Monospace fonts (e.g., *JetBrains Mono*) used exclusively for tracking numbers and warehouse rack codes.

## 4. Component Stylings
*   **Buttons:**
    *   **Shape:** Subtly rounded corners (8px / 0.5rem).
    *   **Style:** Flat primary backgrounds or high-contrast ghost outlines.
    *   **Interaction:** 1px upward translation on hover with a soft glow shadow.
*   **Cards/Containers:**
    *   **Shape:** 12px corner radius (rounded-xl).
    *   **Depth:** Whisper-soft diffused shadows for elevation. No heavy borders.
*   **Inputs/Forms:**
    *   **Stroke:** 1px Border Steel (#334155).
    *   **Background:** Subtle glass-morphism effect (semi-transparent slate).
    *   **Focus:** Glowing Blue ring (2px) with 0.1 opacity.

## 5. Layout Principles
*   **Operational Density:** Interfaces for warehouse operators are data-dense with minimal padding to maximize "information at a glance."
*   **Customer Whitespace:** Interfaces for end-customers (Client Portal) are airy, mobile-first, and use large, touch-friendly cards.
*   **Navigation:** A retractable, high-contrast sidebar on the left and a floating, blurred glass navbar on top.
*   **Timelines:** Package history is always represented by a vertical "Connected Dot" timeline with status-colored connectors.

---
*Created for use with Stitch. Align all generated screens to this semantic design language.*
