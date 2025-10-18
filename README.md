# CanaryTodo

A PHP app demonstrating a 10% canary release of a redesigned UI for a todo list and verification page.

## Setup
1. Install XAMPP (apachefriends.org).
2. Place files in `C:\xampp\htdocs\feature-toggle-demo`.
3. Start Apache, visit `http://localhost/feature-toggle-demo`.

## Features
- Old UI: Modern blue-gray todo list and verification page.
- New UI: Dark blue (#1B3C53, #234C6A) with beige (#D2C1B6) and light blue-gray (#456882), glassmorphism, search, clear, and debug links (10% canary).
- Shared toggle logic in `toggle.php` (uses include_once).
- Verification: `verify.php` simulates 100 users, styled like old/new UI.
- Debug: Bucket and session ID in browser console, `access.log`. Debug links (Force New/Old, Reset) only in new UI.


## Presentation
[Link to Google Slides]
