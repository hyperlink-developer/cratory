# Release Notes

## [v1.0.0-beta] - 2026-07-07

### Added
- **User Management:** Added a new `Settings > User Management` screen to manage organization members, allowing administrators to add staff, accountants, and other org admins.
- **Support Contact Backend:** Contact Support form is now fully functional, powered by a Livewire component that sends emails directly to `cratory.support@yagneshbhanani.com`.
- **Form Rate Limiting:** Added a rate limit (max 3 requests per minute per IP) and a honeypot field to the contact form to aggressively combat spam bots.
- **Premium UI Upgrades:** Upgraded the Contact Support page to feature a dynamic glowing background, floating glass-cards, and smooth native Alpine.js submit animations.

### Changed
- **Organization Roles:** Modifying the default user creation logic; new users no longer default to the `Commander` role. The user who creates an organization is now assigned the `OrgAdmin` role. Commander privileges must be granted manually via the database for maximum security.
- **Pricing Plans:** Removed "API Access" and "Full API Access" bullets from the landing page pricing tiers.
- **Global Support Email:** Updated the support email references across the application (Privacy Policy, Welcome Footer, Contact page) to `cratory.support@yagneshbhanani.com`.
- **Help Center Accordions:** Upgraded the Help Center FAQ accordions to utilize native Alpine.js `x-transition` instead of the `x-collapse` plugin for snappier, smoother animations.

### Fixed
- **Help Center Layout Bug:** Fixed a critical bug where the main content area of the Help Center would overflow off the screen because the public layout was missing the required Livewire scripts necessary to run Alpine.js.
- **Full-Page Screenshot Bug:** Fixing the layout bug inherently resolved the issue where browser screenshot extensions were duplicating the header when capturing the Help Center page.
