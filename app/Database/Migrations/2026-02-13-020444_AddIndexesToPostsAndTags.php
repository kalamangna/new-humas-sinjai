<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesToPostsAndTags extends Migration
{
    public function up()
    {
        // 1. Add indexes to 'posts' table
        $this->db->query("ALTER TABLE posts ADD INDEX idx_status_published (status, published_at)");
        
        // 2. Add FULLTEXT index for search relevance
        $this->db->query("ALTER TABLE posts ADD FULLTEXT idx_fulltext_title_content (title, content)");

        // 3. Add indexes to pivot tables
        $this->db->query("ALTER TABLE post_categories ADD INDEX idx_post_id (post_id)");
        $this->db->query("ALTER TABLE post_categories ADD INDEX idx_category_id (category_id)");
        
        $this->db->query("ALTER TABLE post_tags ADD INDEX idx_post_id (post_id)");
        $this->db->query("ALTER TABLE post_tags ADD INDEX idx_tag_id (tag_id)");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE posts DROP INDEX idx_status_published");
        $this->db->query("ALTER TABLE posts DROP INDEX idx_fulltext_title_content");
        
        $this->db->query("ALTER TABLE post_categories DROP INDEX idx_post_id");
        $this->db->query("ALTER TABLE post_categories DROP INDEX idx_category_id");
        
        $this->db->query("ALTER TABLE post_tags DROP INDEX idx_post_id");
        $this->db->query("ALTER TABLE post_tags DROP INDEX idx_tag_id");
    }
}
