# IncludeWP

[IncludeWP.com](http://includewp.com), a leaderboard of the top open-source frameworks for WordPress plugin & theme developers.

## Why?
Because code reusability is awesome!

Earlier this year (March 2016), the WordPress.org plugins review team issued a statement on make.wordpress.org that [frameworks are no longer allowed in the official plugins repository](https://make.wordpress.org/plugins/2016/03/01/please-do-not-submit-frameworks/). So... we decided to build a new cozy home for all frameworks, making it easier for developers to finds what's out there to reuse before starting to work on a new project.

## Contributing

Missing a framework here? Just fork the repo and add your framework
as a `<name>.php` in the `src/frameworks` folder.

Make sure to follow the following rules:

*   **GPL Compatible License:** The framework must be licensed with a [GPL competible license](https://www.gnu.org/philosophy/license-list.html#GPLCompatibleLicenses) so plugins and themes that use it can be listed on WordPress.org.
*   **GitHub:** The framework must have a public repository on GitHub that we can link to and pull in stats from.
*   **Stick to the format:** Fill out all the same fields as the other frameworks in `src/frameworks`.
*   **WordPress.org (optional):** If the framework is listed as a plugin on wp.org, please add a reference to the plugin's slug.
*   **Short description:** Keep the description for the overview page short and sweet. No need to mention it's a "framework" or adding "WordPress" in it. Please keep it informative, it's not a sales pitch :)
*   **Thumbprint:** If you want the framework to automatically list all plugins & themes from WordPress.org that are using it, you need to provide a unique combination of a file name and a string, which we'll use for identification. If you are adding a starter theme, make sure that the thumbprint is not changed when implementing the theme.

## Running locally

IncludeWP is built on pure PHP and JavaScript so it should be working out of the box.

If you'd like to keep the GitHub stats and framework screenshots up to date, you should execute `cronjobs/weekly-update.php` on a weekly basis.
Before you do that, you'll need to update `includes/config.php` with your keys/tokens:

1. Sign-up for a free account via [Page2Images](http://www.page2images.com) and set `PAGE_2_IMAGES_REST_KEY` & `PAGE_2_IMAGES_KEY` with your keys.
2. Create your [personal GitHub token](https://github.com/settings/tokens/new) and set `GITHUB_ACCESS_TOKEN` with the token.

## What do we use?

### Frontend
* [Materialize](https://materializecss.com)
* [jQuery](https://jquery.com/)
* [Font Awesome](https://fontawesome.io)
* [Google Fonts] (https://www.google.com/fonts)

### APIs
* Addendio: Pulling WordPress.org plugins & themes based on a slug ([private API by Addendio](https://addendio.com/)).
* GitHub: Pulling the framework's GitHub repo stats (https://api.github.com).
* WordPress.org Plugins: Pulling the framework's banner (https://api.wordpress.org/plugins/info/1.0/).
* Page2Images: Generating a screenshot of the framework's homepage (http://api.page2images.com).
* Cloudinary: Images CDN (https://res.cloudinary.com).

## Freemius

IncludeWP is built and maintained with ❤ by [Freemius](https://freemius.com) -- [Analytics](https://freemius.com/wordpress/insights/), [Monetization](https://freemius.com/wordpress/checkout/) and [Marketing Automation](https://freemius.com/#automation) platform for WordPress theme & plugin developers.

A big shout out to Luca Fracassi from [Addendio](https://addendio.com/), a search engine for WordPress plugins & themes, who helped us detecting on which WordPress plugins/themes frameworks are being used.

## License
This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).
