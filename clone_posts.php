<?php
function cloneWpPosts()
{
    // Get the JSON
    $json = file_get_contents('http://[your-site.com]/wp-json/wp/v2/posts?per_page=100');
    // Convert the JSON to an array of posts
    $posts = json_decode($json);
    // cycle through posts
    foreach ($posts as $p) {
        // insert posts into WP
        try {
            $post_id = wp_insert_post(
                array(
                    'post_title' => $p->title->rendered,
                    'post_content' => $p->content->rendered,
                    'post_status'   => 'publish',
                    'post_excerpt' => $p->excerpt->rendered,
                    'comment_status' => 'closed'
                )
            );

            try {
                // get featured media img and upload it to WP
                $fmedia_api = $p->_links->{"wp:featuredmedia"}[0]->href;
                $media_json = file_get_contents($fmedia_api);
                $media = json_decode($media_json);
                $file_url = $media->source_url;
                $get = wp_remote_get($file_url);
                $mirror = wp_upload_bits(basename($file_url), '', wp_remote_retrieve_body($get));
                $wp_upload_dir = wp_upload_dir();
                $attach_id = wp_insert_attachment(array(
                    'post_mime_type' => 'image/jpg',
                    'post_title'     => basename($file_url),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                    ), $mirror['file'], 0);
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $mirror['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // upload featured media img to post
                set_post_thumbnail($post_id, $attach_id);
            } catch (Exception $e) {
                //null
            }
        } catch (Exception $e) {
            //null
        }
    }
}
