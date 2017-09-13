
data/config.php 数据库/session/auth_key 等配置文件


includes/lib_main.php 

assign_template();

show_message();


includes/init.php 设置模板目录 Line 186 ~ 192

    $smarty->cache_lifetime = $_CFG['cache_time'];
	// 设置模板目录路径
    $smarty->template_dir   = ROOT_PATH . 'themes/' . $_CFG['template'];
	// 设置缓存目录
    $smarty->cache_dir      = ROOT_PATH . 'temp/caches';
	// 设置文件编译
    $smarty->compile_dir    = ROOT_PATH . 'temp/compiled';
