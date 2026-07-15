<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Template_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'index' => [
                'default' => 'index.php',
                'description' => 'Generic fallback template',
            ],
            'front-page' => [
                'default' => 'front-page.php',
                'theme_fallback' => 'front-page.php',
                'description' => 'Front page template',
            ],
            'home' => [
                'default' => 'home.php',
                'theme_fallback' => 'home.php',
                'description' => 'Posts page (blog index) template',
            ],
            'single' => [
                'default' => 'single.php',
                'theme_fallback' => 'single.php',
                'kids_collection' => 'templates/kids-collection/blog.php',
                'description' => 'Single post template',
            ],
            'single-portfolio' => [
                'default' => 'single-portfolio.php',
                'theme_fallback' => 'single-portfolio.php',
                'description' => 'Single portfolio project template',
            ],
            'page' => [
                'default' => 'page.php',
                'theme_fallback' => 'page.php',
                'description' => 'Default page template',
            ],
            'page-kids-collection' => [
                'default' => 'page-kids-collection.php',
                'theme_fallback' => 'page-kids-collection.php',
                'description' => 'Kids Collection landing page template',
            ],
            'archive' => [
                'default' => 'archive.php',
                'theme_fallback' => 'archive.php',
                'description' => 'Archive index template',
            ],
            'archive-portfolio' => [
                'default' => 'archive-portfolio.php',
                'theme_fallback' => 'archive-portfolio.php',
                'description' => 'Portfolio archive template',
            ],
            'search' => [
                'default' => 'search.php',
                'theme_fallback' => 'search.php',
                'description' => 'Search results template',
            ],
            '404' => [
                'default' => '404.php',
                'theme_fallback' => '404.php',
                'kids_collection' => 'templates/kids-collection/404.php',
                'description' => '404 error template',
            ],
            'singular' => [
                'default' => 'singular.php',
                'theme_fallback' => 'singular.php',
                'description' => 'Generic singular post template',
            ],
            'attachment' => [
                'default' => 'attachment.php',
                'theme_fallback' => 'attachment.php',
                'description' => 'Attachment page template',
            ],
            'author' => [
                'default' => 'author.php',
                'theme_fallback' => 'author.php',
                'description' => 'Author archive template',
            ],
            'category' => [
                'default' => 'category.php',
                'theme_fallback' => 'category.php',
                'description' => 'Category archive template',
            ],
            'tag' => [
                'default' => 'tag.php',
                'theme_fallback' => 'tag.php',
                'description' => 'Tag archive template',
            ],
            'taxonomy' => [
                'default' => 'taxonomy.php',
                'theme_fallback' => 'taxonomy.php',
                'description' => 'Custom taxonomy archive template',
            ],
            'date' => [
                'default' => 'date.php',
                'theme_fallback' => 'date.php',
                'description' => 'Date archive template',
            ],
            'comments' => [
                'default' => 'comments.php',
                'theme_fallback' => 'comments.php',
                'description' => 'Comments template',
            ],
            'sidebar' => [
                'default' => 'sidebar.php',
                'theme_fallback' => 'sidebar.php',
                'description' => 'Sidebar template',
            ],
            'header' => [
                'default' => 'header.php',
                'theme_fallback' => 'header.php',
                'description' => 'Site header template',
            ],
            'footer' => [
                'default' => 'footer.php',
                'theme_fallback' => 'footer.php',
                'description' => 'Site footer template',
            ],
        ];
    }

    public function resolve(string $type): ?string {
        $entry = $this->entries[$type] ?? null;
        if (null === $entry) {
            return null;
        }

        $profile_path = $this->get_profile_path();
        $profile_file = $profile_path . '/' . $entry['default'];
        if (file_exists($profile_file)) {
            return $profile_file;
        }

        $default_path = get_template_directory() . '/profiles/default/' . $entry['default'];
        if (file_exists($default_path)) {
            return $default_path;
        }

        $kids_collection = $entry['kids_collection'] ?? null;
        if ($kids_collection) {
            $kc_path = get_template_directory() . '/' . $kids_collection;
            if (file_exists($kc_path)) {
                return $kc_path;
            }
        }

        $theme_fallback = $entry['theme_fallback'] ?? null;
        if ($theme_fallback) {
            $tf_path = get_template_directory() . '/' . $theme_fallback;
            if (file_exists($tf_path)) {
                return $tf_path;
            }
        }

        return null;
    }

    public function get_template_path(string $type): ?string {
        $entry = $this->entries[$type] ?? null;
        if (null === $entry) {
            return null;
        }
        return $entry['default'] ?? null;
    }

    private function get_profile_path(): string {
        $profile = get_option('optix_active_profile', 'default');
        if (!is_string($profile) || empty($profile)) {
            $profile = 'default';
        }
        $profile = sanitize_file_name($profile);
        return get_template_directory() . '/profiles/' . $profile;
    }
}
