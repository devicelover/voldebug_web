# Database Migrations

## AI School Gallery

To enable the dynamic AI School events gallery, run the following SQL in your database:

```sql
-- File: ai_school_gallery.sql
CREATE TABLE IF NOT EXISTS `ai_school_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**How to run:**
1. Open phpMyAdmin or your MySQL client
2. Select the `u993820046_voldebug` database
3. Go to SQL tab and paste the above query
4. Click Execute

**Admin access:** After creating the table, go to Admin Panel → AI School Gallery to add event photos.
