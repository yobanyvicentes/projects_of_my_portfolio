# FlatApp Design System

FlatApp is a mobile-first collaborative app for flatmates who share a home. The interface must feel like a friendly shared-living app, not a cold admin dashboard.

This design system defines the visual direction, interaction rules, and reusable UI principles for the Laravel + Blade + Tailwind implementation.

## 1. Product Personality

FlatApp should feel:

- Direct
- Modern
- High contrast
- Mobile-first
- Collaborative
- Clear and action-oriented
- Slightly futuristic without feeling childish

The app language is English. All user-facing labels, buttons, empty states, validation messages, navigation items, and onboarding copy should be written in clear English.

## 2. Visual Direction

The visual identity is based on:

- Deep black backgrounds
- White text
- Bright neon blue accents
- Strong contrast
- Cards instead of tables
- Simple icons for modules
- Minimal but expressive UI states

FlatApp must avoid pale, washed-out colors. Accent colors should feel slightly fluorescent while still remaining readable and accessible.

## 3. Color Tokens

### Core Colors

| Token | Hex | Usage |
| --- | --- | --- |
| `flatapp-black` | `#000000` | Main app background |
| `flatapp-ink` | `#05070A` | Primary dark surface |
| `flatapp-surface` | `#0B0F14` | Cards, panels, elevated sections |
| `flatapp-surface-soft` | `#111827` | Secondary cards and grouped areas |
| `flatapp-border` | `#1F2937` | Card borders and separators |
| `flatapp-white` | `#FFFFFF` | Primary text |
| `flatapp-muted` | `#9CA3AF` | Secondary text |
| `flatapp-neon` | `#00D4FF` | Primary actions, links, active states |
| `flatapp-neon-strong` | `#00A8FF` | Hover and pressed states |
| `flatapp-neon-soft` | `#7DEBFF` | Subtle highlights and icon accents |

### Semantic Colors

Semantic colors should be used sparingly. They must stay vivid, not pastel.

| Token | Hex | Usage |
| --- | --- | --- |
| `flatapp-success` | `#22C55E` | Completed chores, paid status, positive feedback |
| `flatapp-warning` | `#FACC15` | Pending approvals, due soon states |
| `flatapp-danger` | `#FF3B5C` | Rejections, destructive actions, overdue states |
| `flatapp-purple` | `#A855F7` | Optional secondary module accent |
| `flatapp-lime` | `#A3FF12` | Optional energetic highlight |

## 4. Accessibility Rules

- Text must remain readable on dark backgrounds.
- Primary buttons must have strong contrast.
- Neon blue should not be used for long paragraphs.
- Focus states must be visible and keyboard friendly.
- Icons must be paired with labels where the action is not obvious.
- Forms should include helper text for fields that may be unclear.

## 5. Typography

Default font stack:

```css
'Instrument Sans', ui-sans-serif, system-ui, sans-serif
```

### Type Scale

| Style | Tailwind size | Usage |
| --- | --- | --- |
| Display | `text-3xl` / `text-4xl` | Landing and onboarding hero titles |
| Page title | `text-2xl` | Main screen titles |
| Section title | `text-lg` / `text-xl` | Card and module section titles |
| Body | `text-sm` / `text-base` | Main content |
| Caption | `text-xs` | Metadata, timestamps, helper copy |

### Copy Principles

Use simple English. Prefer:

- “Create flat” instead of “Initiate household entity”
- “Join with code” instead of “Submit membership request”
- “Pending approval” instead of “Awaiting administrative authorization”

## 6. Layout Principles

FlatApp is mobile-first.

### Mobile

- Single-column layout
- Cards stacked vertically
- Persistent or easy-access bottom navigation
- Primary CTA visible near the top or bottom of the screen
- No dense tables
- Forms should be short and grouped

### Desktop

- Keep mobile mental model
- Use wider cards and grids
- Do not turn the app into a traditional admin panel
- Use side-by-side cards only when it improves scanning

## 7. Components

### App Shell

The main app shell should include:

- Top context area with current flat name
- Simple module title
- Bottom navigation or compact modular navigation
- Safe mobile spacing
- Dark background

### Cards

Cards are the default container for data.

Recommended base style:

```html
<div class="rounded-2xl border border-flatapp-border bg-flatapp-surface p-5 shadow-flatapp-card">
    ...
</div>
```

Cards should be used for:

- Flats
- Members
- Chores
- Shopping items
- Receipts
- Activity log entries
- Dashboard summaries

### Buttons

#### Primary Button

Used for main actions.

Examples:

- Create flat
- Join flat
- Add chore
- Add item
- Upload receipt
- Approve request

Style direction:

- Neon blue background
- Black text
- Rounded full or rounded-xl
- Strong hover state
- Visible focus ring

#### Secondary Button

Used for supporting actions.

Examples:

- View details
- Cancel
- Edit profile

Style direction:

- Dark surface
- Border
- White text
- Neon hover border

#### Danger Button

Used for destructive actions.

Examples:

- Remove member
- Reject request
- Delete item

Style direction:

- Dark background
- Red border or red text
- Clear confirmation when destructive

### Inputs

Inputs should be large enough for mobile.

Base direction:

- Dark surface
- White text
- Muted placeholder
- Neon focus ring
- Rounded-xl

### Badges

Badges should communicate state quickly.

Examples:

- Admin
- Flatmate
- Landlord
- Pending
- Approved
- Due today
- Completed

Badges should be vivid but compact.

### Empty States

Every module must include useful empty states.

Empty states should include:

- Short title
- One-line explanation
- Clear next action

Examples:

- “No chores yet”
- “Start by adding the first shared task for this flat.”
- CTA: “Add chore”

## 8. Module Identity

Each module should have a recognizable icon and accent treatment.

| Module | Suggested accent | Purpose |
| --- | --- | --- |
| Flats | Neon blue | Core flat context |
| Members | Purple | People and roles |
| Chores | Lime | Tasks and completion |
| Shopping | Yellow | Shared shopping list |
| Receipts | Pink / danger-red accent | Uploaded bills and proof |
| Activity | Neon soft | Shared transparency |
| Settings | Muted / border-driven | Configuration |

Module colors are accents only. The main app should remain black, white, and neon blue.

## 9. Navigation

Navigation must be simple and app-like.

Primary modules:

- Home
- Chores
- Shopping
- Receipts
- Activity
- Settings

Mobile navigation should avoid overcrowding. If needed, group secondary items under “More”.

## 10. Motion and Interaction

Motion should be subtle and fast.

Use:

- Quick hover transitions
- Slight card lift on interactive cards
- Glow on focused primary actions
- Smooth state changes

Avoid:

- Slow animations
- Overly playful effects
- Motion that blocks task completion

## 11. Forms

Forms should be simple and direct.

Rules:

- One primary action per form
- Short labels
- Helpful placeholders
- Error messages in plain English
- Avoid long forms on mobile
- Break large flows into smaller steps where needed

## 12. Content Rules

All UI copy must be in English.

Tone:

- Clear
- Friendly
- Practical
- Not corporate

Examples:

- “Your request is pending approval.”
- “Ask an admin to approve your request.”
- “This flat does not have chores yet.”
- “Upload a receipt so everyone can see it.”

## 13. Implementation Notes

This project uses Tailwind CSS v4. Design tokens should be defined in `resources/css/app.css` using the `@theme` block.

Avoid introducing large UI libraries unless there is a strong reason. Blade components should be preferred for reusable UI patterns.

Recommended future Blade components:

- `x-app-shell`
- `x-flat-card`
- `x-module-card`
- `x-primary-button`
- `x-secondary-button`
- `x-danger-button`
- `x-empty-state`
- `x-status-badge`
- `x-bottom-navigation`

## 14. Design Acceptance Checklist

Before merging UI-related PRs, verify:

- The screen works well on mobile width.
- The primary CTA is obvious.
- No table is used where cards would be better.
- Empty states are present.
- Text is in English.
- Colors follow black, white, and neon blue direction.
- Focus states are visible.
- The UI feels collaborative, not administrative.
