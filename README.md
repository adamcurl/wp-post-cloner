## Setup (works as of now, working on better solution)

1. Copy the `clone_posts.php` file into your WP install

2. In `clone_posts.php`, replace `[your-site]` with the site who's posts you wish to duplicate

3. Call `cloneWpPosts()` in your `functions.php`

4. Refresh any page on your site, then remove `cloneWpPosts()` function call

   - this will call `cloneWpPosts()` on reload and clone all posts from your target site to your site

5. All posts and featured media images should now be in your site
