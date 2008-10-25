DELETE FROM {DB_PREFIX}modules_config WHERE `module_id` = "{module_id}";

DELETE FROM {DB_PREFIX}modules WHERE `id` = {module_id} AND `name` = "{name}";

DROP TABLE {DB_PREFIX}module_text;
