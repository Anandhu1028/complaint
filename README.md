# Premium Public Complaint Portal — Laravel Module

This package is a Laravel-ready implementation of the complaint portal UI and workflow.

## Includes
- Premium responsive public landing page
- Complaint submission form
- Complaint reference generation
- MySQL migration/model
- Automatic email via Laravel Mail to an official recipient configured in `.env`
- Complaint tracking page
- Admin login using environment credentials
- Admin dashboard, search, filters, status updates and complaint detail view
- File uploads with validation
- CSRF, validation and basic rate limiting

## Install
1. Copy these files into a fresh Laravel 12+ project.
2. Run `php artisan migrate`.
3. Add the `.env` values from `.env.example`.
4. Configure SMTP using your real domain/mail provider.
5. Set `COMPLAINT_RECIPIENT_EMAIL` to the official published recipient address.
6. Run `php artisan storage:link`.
7. Add the routes from `routes/web.php` if your project has other routes.

## Admin
Admin credentials are configured in `.env` for this module. Change them before deployment.

## Important
The site should not claim to be an official government website unless you have authorization. Use the official government recipient address only if it has been verified/published by the relevant authority.
