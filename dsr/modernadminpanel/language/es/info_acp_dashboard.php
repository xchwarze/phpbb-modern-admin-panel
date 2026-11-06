<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// Some characters you may want to copy&paste: ’ » “ ” …
$lang = array_merge($lang, [
	// ACP modules and log entries
	'STUDIO_PREMIUM_TOOLTIP' => 'Módulos premium',
	'STUDIO_PREMIUM_EYEBROW' => 'Módulos de pago opcionales',
	'STUDIO_PREMIUM_TITLE' => 'Potenciá tu comunidad',
	'STUDIO_PREMIUM_INTRO' => 'Estos módulos independientes están disponibles como opciones de pago. No limitan ninguna función de Modern Admin Panel.',
	'STUDIO_PREMIUM_OAUTH_TITLE' => 'OAuth Register',
	'STUDIO_PREMIUM_OAUTH_TEXT' => 'Permití que tus miembros inicien sesión o se registren mediante proveedores de identidad externos.',
	'STUDIO_PREMIUM_SPAM_TITLE' => 'Spam Guard',
	'STUDIO_PREMIUM_SPAM_TEXT' => 'Combiná filtros manuales con detección de spam asistida por IA.',
	'STUDIO_PREMIUM_ASYNC_TITLE' => 'Async Notifications',
	'STUDIO_PREMIUM_ASYNC_TEXT' => 'Procesá notificaciones en segundo plano para foros grandes.',
	'STUDIO_PREMIUM_PUSH_TITLE' => 'Push Notifications',
	'STUDIO_PREMIUM_PUSH_TEXT' => 'Enviá alertas push a los usuarios del foro.',
	'STUDIO_PREMIUM_APPS_TITLE' => 'Aplicaciones Android e iOS',
	'STUDIO_PREMIUM_APPS_TEXT' => 'Aplicaciones móviles integradas para tu foro.',
	'STUDIO_PREMIUM_CUSTOM_TITLE' => 'Extensiones a medida',
	'STUDIO_PREMIUM_CUSTOM_TEXT' => 'Convertí tu idea en una función hecha para tu comunidad. Diseñamos extensiones integradas a tu foro y adaptadas a tus necesidades.',
	'STUDIO_PREMIUM_CLOSE' => 'Cerrar',
	'STUDIO_PREMIUM_CONTACT' => 'Consultar por email',
	'STUDIO_PREMIUM_SUBJECT' => 'Consulta sobre módulos premium',
	'STUDIO_PREMIUM_BODY' => 'Me interesa alguno de los módulos pagos.',
	'ACP_INDEX'								=> 'Resumen',
	'ACP_ADMIN_DASHBOARD_TITLE'				=> 'Modern Admin Panel',
	'ACP_ADMIN_DASHBOARD_SEARCH'			=> 'Búsqueda en el PCA',
	'ACP_ADMIN_DASHBOARD_SETTINGS'			=> 'Configuración',
	'LOG_ADMIN_DASHBOARD_NOTES'				=> '<strong>Editadas las notas de los administradores</strong>',
	'LOG_ADMIN_DASHBOARD_SETTINGS'			=> '<strong>Cambiada la configuración de Modern Admin Panel</strong>',

	// Sidebar, header and page bar
	'STUDIO_DASHBOARD_ACCOUNT'				=> 'Cuenta',
	'STUDIO_DASHBOARD_BREADCRUMBS'			=> 'Usted está aquí',
	'STUDIO_DASHBOARD_HOME'					=> 'Inicio',
	'STUDIO_DASHBOARD_HOME_TOGGLE'			=> 'Mostrar u ocultar los módulos fijados',
	'STUDIO_DASHBOARD_MENU'					=> 'Menú de administración',
	'STUDIO_DASHBOARD_MENU_CLOSE'			=> 'Cerrar el menú',
	'STUDIO_DASHBOARD_MENU_HERE'			=> '(contiene la página actual)',
	'STUDIO_DASHBOARD_MENU_OPEN'			=> 'Abrir el menú',
	'STUDIO_DASHBOARD_MENU_PIN'				=> 'Fijar el menú',
	'STUDIO_DASHBOARD_MENU_PIN_KEEP'		=> 'Mantener el menú abierto (Ctrl+B)',
	'STUDIO_DASHBOARD_MENU_PIN_RELEASE'		=> 'Dejar de fijar el menú y mostrar solo los iconos (Ctrl+B)',
	'STUDIO_DASHBOARD_MODE'					=> 'Modo de color: %s',
	'STUDIO_DASHBOARD_MODE_DARK'			=> 'Oscuro',
	'STUDIO_DASHBOARD_MODE_LIGHT'			=> 'Claro',
	'STUDIO_DASHBOARD_MODE_SYSTEM'			=> 'Automático',
	'STUDIO_DASHBOARD_PIN'					=> 'Fijar este módulo en el menú',
	'STUDIO_DASHBOARD_PINNED'				=> 'Fijados',
	'STUDIO_DASHBOARD_PINS_EMPTY'			=> 'Fijar módulos con %s',
	'STUDIO_DASHBOARD_RECENT'				=> 'Recientes',
	'STUDIO_DASHBOARD_SEARCH_ACP'			=> 'Buscar en el PCA',
	'STUDIO_DASHBOARD_SKIP'					=> 'Saltar al contenido',

	// Command palette
	'STUDIO_DASHBOARD_MODULES'					=> 'Módulos del menú',
	'STUDIO_DASHBOARD_PALETTE'					=> 'Ir a un módulo',
	'STUDIO_DASHBOARD_PALETTE_EMPTY'			=> 'Nada coincide.',
	'STUDIO_DASHBOARD_PALETTE_ERROR'			=> 'Falló la búsqueda.',
	'STUDIO_DASHBOARD_PALETTE_HINT'				=> '↑ ↓ elegir · Intro abrir · Esc cerrar',
	'STUDIO_DASHBOARD_PALETTE_LOADING'			=> 'Buscando…',
	'STUDIO_DASHBOARD_PALETTE_PLACEHOLDER'		=> 'Escriba el nombre de un módulo…',
	'STUDIO_DASHBOARD_PALETTE_PLACEHOLDER_USERS'	=> 'Escriba el nombre de un módulo, o @ y el de un usuario…',
	'STUDIO_DASHBOARD_PALETTE_SEARCH_PAGE'		=> 'Buscar “%s” en la configuración del Sitio',
	'STUDIO_DASHBOARD_PALETTE_SETTINGS_GROUP'	=> 'Configuración',
	'STUDIO_DASHBOARD_PALETTE_START'			=> 'Empiece a escribir para buscar en el menú. Aquí se listan sus módulos recientes y fijados.',
	'STUDIO_DASHBOARD_PALETTE_START_USERS'		=> 'Empiece a escribir para buscar en el menú, o escriba @ y el nombre de un usuario. Aquí se listan sus módulos recientes y fijados.',
	'STUDIO_DASHBOARD_PALETTE_START_NO_PINS'		=> 'Empiece a escribir para buscar en el menú. Aquí se listan sus módulos recientes.',
	'STUDIO_DASHBOARD_PALETTE_START_USERS_NO_PINS'		=> 'Empiece a escribir para buscar en el menú, o escriba @ y el nombre de un usuario. Aquí se listan sus módulos recientes.',
	'STUDIO_DASHBOARD_PALETTE_ACTIONS_GROUP'	=> 'Acciones',
	'STUDIO_DASHBOARD_PALETTE_FORUMS_GROUP'		=> 'Foros',
	'STUDIO_DASHBOARD_PALETTE_GROUPS_GROUP'		=> 'Grupos',
	'STUDIO_DASHBOARD_PALETTE_USERS_GROUP'		=> 'Usuarios',
	'STUDIO_DASHBOARD_PALETTE_USERS_HINT'		=> 'Escriba al menos 2 letras del nombre de un usuario tras la @.',

	// Actions of the command palette: plain links, listed for administrators who may use them
	'STUDIO_DASHBOARD_ACTION_CONFIRMS'			=> 'Pide confirmación antes',
	'STUDIO_DASHBOARD_ACTION_PURGE_CACHE'		=> 'Purgar caché',
	'STUDIO_DASHBOARD_ACTION_RESET_DATE'		=> 'Reiniciar fecha de inicio del foro',
	'STUDIO_DASHBOARD_ACTION_RESYNC_STATS'		=> 'Resincronizar estadísticas',
	'STUDIO_DASHBOARD_ACTION_RESET_ONLINE'		=> 'Reiniciar récord de usuarios en línea',
	'STUDIO_DASHBOARD_ACTION_RESYNC_POSTCOUNTS'	=> 'Resincronizar contadores de mensajes',
	'STUDIO_DASHBOARD_ACTION_RESYNC_DOTS'		=> 'Resincronizar temas con punto',
	'STUDIO_DASHBOARD_ACTION_PURGE_SESSIONS'	=> 'Purgar sesiones',
	'STUDIO_DASHBOARD_ACTION_VIEW_LOG'			=> 'Ver log de administración',
	'STUDIO_DASHBOARD_ACTION_DISABLE_BOARD'		=> 'Desactivar el foro',
	'STUDIO_DASHBOARD_ACTION_DISABLE_BOARD_HINT'	=> 'Abre la configuración del foro. No cambia nada hasta que guarde.',

	// Dashboard
	'STUDIO_DASHBOARD_ADMIN_NOTES'			=> 'Notas de los administradores',
	'STUDIO_DASHBOARD_AVATARS'				=> 'Avatares',
	'STUDIO_DASHBOARD_BOARD'				=> 'Sitio',
	'STUDIO_DASHBOARD_BOARD_SETTINGS'		=> 'Configuración del Sitio',
	'STUDIO_DASHBOARD_GZIP'					=> 'Gzip',
	'STUDIO_DASHBOARD_NOTES_EXPLAIN'		=> 'Todos los administradores pueden leer estas notas. Se admite BBCode, excepto imágenes y Flash.',
	'STUDIO_DASHBOARD_ORPHANS'				=> [
		1 => '%d archivo huérfano',
		2 => '%d archivos huérfanos',
	],
	'STUDIO_DASHBOARD_PER_DAY'				=> '%s por día',
	'STUDIO_DASHBOARD_PENDING'				=> 'Requiere atención',
	'STUDIO_DASHBOARD_PENDING_CLEAR'		=> 'Todo en orden',
	'STUDIO_DASHBOARD_PENDING_INACTIVE'		=> 'Usuarios inactivos',
	'STUDIO_DASHBOARD_PENDING_PM_REPORTS'	=> 'Reportes de MP',
	'STUDIO_DASHBOARD_PENDING_POSTS'		=> 'Mensajes por aprobar',
	'STUDIO_DASHBOARD_PENDING_REPORTS'		=> 'Reportes de mensajes',
	'STUDIO_DASHBOARD_PENDING_TOPICS'		=> 'Temas por aprobar',
	// The thousands separator of the language, for the counts of that list (the board has no helper for it)
	'STUDIO_DASHBOARD_THOUSANDS_SEPARATOR'	=> '.',

	// Search page
	'STUDIO_DASHBOARD_MODULES_MATCHING'		=> 'Módulos encontrados',
	'STUDIO_DASHBOARD_MODULES_NONE'			=> 'Ningún módulo coincide con lo escrito.',
	'STUDIO_DASHBOARD_RESULTS_NO'			=> 'No se encontró nada',
	'STUDIO_DASHBOARD_RESULTS_NONE'			=> 'Ninguna opción coincide con lo escrito.',
	'STUDIO_DASHBOARD_RESULTS_NOTE'			=> 'Que un resultado no aparezca no significa que la opción no exista.<br>La búsqueda de configuración solo abarca una parte del PCA, sobre todo las opciones de la pestaña “General”.',

	// Settings page (Extensions » Modern Admin Panel » Settings)
	'STUDIO_DASHBOARD_SETTINGS_TITLE'		=> 'Configuración de Modern Admin Panel',
	'STUDIO_DASHBOARD_SETTINGS_EXPLAIN'		=> 'Esta configuración es común a todos los administradores. El estado del menú (fijado o no), los módulos fijados, los recientes y el modo de color los guarda cada navegador.',
	'STUDIO_DASHBOARD_SETTINGS_INVALID'		=> 'Alguno de los valores enviados no es válido.',
	'STUDIO_DASHBOARD_APPEARANCE'			=> 'Encabezado',
	'STUDIO_DASHBOARD_MENU_LEGEND'		=> 'Menú lateral',
	'STUDIO_DASHBOARD_HEADER_COLOUR'		=> 'Color',
	'STUDIO_DASHBOARD_HEADER_COLOUR_EXPLAIN'	=> 'La barra superior. En modo oscuro se pinta de gris oscuro y este color no se usa.',
	'STUDIO_DASHBOARD_SIDEBAR_COLOUR'		=> 'Color',
	'STUDIO_DASHBOARD_SIDEBAR_COLOUR_EXPLAIN'	=> 'También es el color de acento: enlaces, botones, anillos de foco y la búsqueda. En modo oscuro el menú se pinta de gris oscuro y este color queda solo como acento.',
	'STUDIO_DASHBOARD_SIDEBAR_CORNER'		=> 'Forma de las filas',
	'STUDIO_DASHBOARD_SIDEBAR_CORNER_EXPLAIN'	=> 'Redondeadas, cuadradas o a todo el ancho.',
	'STUDIO_DASHBOARD_PALETTE_LEGEND'		=> 'Búsqueda (Ctrl+K)',
	'STUDIO_DASHBOARD_PALETTE_SETTINGS'		=> 'Incluir opciones de configuración',
	'STUDIO_DASHBOARD_PALETTE_SETTINGS_EXPLAIN'	=> 'Además de los módulos, encuentra las opciones de configuración que coinciden con lo escrito (desde 3 caracteres). Cada administrador solo ve las de las páginas que puede abrir.',
	'STUDIO_DASHBOARD_PALETTE_JUMPS'		=> 'Incluir usuarios, foros, grupos y acciones',
	'STUDIO_DASHBOARD_PALETTE_JUMPS_EXPLAIN'	=> 'Encuentra usuarios (con @ y el nombre), foros y grupos (desde 3 caracteres), y lista acciones de mantenimiento como purgar la caché, que piden confirmación. Cada administrador solo ve lo que sus permisos le permiten.',
	'STUDIO_DASHBOARD_PALETTE_PINS'			=> 'Mostrar los módulos fijados',
	'STUDIO_DASHBOARD_PALETTE_PINS_EXPLAIN'		=> 'Al abrirla sin haber escrito nada, lista primero los módulos fijados con la estrella (☆). En el menú lateral siguen apareciendo bajo Inicio.',
	'STUDIO_DASHBOARD_INDEX_LEGEND'			=> 'Página de inicio del PCA',

	// The yes/no options: STUDIO_DASHBOARD_<NAME> and STUDIO_DASHBOARD_<NAME>_EXPLAIN (adm/style/acp_admindashboard_settings.html)
	'STUDIO_DASHBOARD_DISPLAY_PENDING'			=> 'Mostrar “Requiere atención”',
	'STUDIO_DASHBOARD_DISPLAY_PENDING_EXPLAIN'	=> 'Temas y mensajes pendientes de aprobar, reportes abiertos y usuarios inactivos, y un aviso si el Sitio está deshabilitado. Cada administrador solo ve los totales de lo que puede gestionar.',
	'STUDIO_DASHBOARD_DISPLAY_STATS'			=> 'Mostrar las estadísticas',
	'STUDIO_DASHBOARD_DISPLAY_STATS_EXPLAIN'	=> 'Estadísticas del foro e información del servidor.',
	'STUDIO_DASHBOARD_REMODEL_STATS'			=> 'Mostrar las estadísticas como tarjetas',
	'STUDIO_DASHBOARD_REMODEL_STATS_EXPLAIN'	=> 'En lugar de las tablas estándar de phpBB. Sin efecto si las estadísticas están ocultas.',
	'STUDIO_DASHBOARD_DISPLAY_NOTES'			=> 'Mostrar las notas de administración',
	'STUDIO_DASHBOARD_DISPLAY_NOTES_EXPLAIN'	=> 'Notas compartidas entre administradores. Las edita cualquier administrador con permiso para cambiar la configuración del foro.',
	'STUDIO_DASHBOARD_DISPLAY_QA'				=> 'Mostrar acceso rápido',
	'STUDIO_DASHBOARD_DISPLAY_QA_EXPLAIN'		=> 'Una tarjeta con los módulos de la categoría “Acceso rápido”.',
	'STUDIO_DASHBOARD_SHOW_QA_MENU'				=> 'Mostrar acceso rápido',
	'STUDIO_DASHBOARD_SHOW_QA_MENU_EXPLAIN'		=> 'Desactívelo para acortar el menú si el acceso rápido ya aparece en la página de inicio.',
	'STUDIO_DASHBOARD_QA_EDIT'					=> 'Editar el acceso rápido',
	'STUDIO_DASHBOARD_QA_HELP_GLOBAL_TITLE'	=> 'El acceso rápido es global',
	'STUDIO_DASHBOARD_QA_HELP_GLOBAL'			=> 'Es una categoría propia de phpBB (ACP_QUICK_ACCESS), igual para todos los administradores. Se edita en PCA » Sistema » Gestión de módulos.',
	'STUDIO_DASHBOARD_QA_HELP_PINS_TITLE'		=> 'Los fijados (☆) son personales',
	'STUDIO_DASHBOARD_QA_HELP_PINS'			=> 'Cada administrador fija módulos con la estrella. Se guardan en su navegador y aparecen bajo Inicio.',
	'STUDIO_DASHBOARD_DISPLAY_LOGS'				=> 'Mostrar el registro de administración',
	'STUDIO_DASHBOARD_DISPLAY_LOGS_EXPLAIN'		=> 'Las últimas entradas, solo para quien puede ver el registro.',
	'STUDIO_DASHBOARD_DISPLAY_USERS'			=> 'Mostrar los usuarios inactivos',
	'STUDIO_DASHBOARD_DISPLAY_USERS_EXPLAIN'	=> 'Los más recientes, solo para quien puede gestionar usuarios.',

	// Colores y formas: STUDIO_DASHBOARD_CHOICE_<VALOR> (controller/settings.php)
	'STUDIO_DASHBOARD_CHOICE_FULL'	=> 'Ancho completo',
	'STUDIO_DASHBOARD_CHOICE_SQUARED'	=> 'Cuadrado',
	'STUDIO_DASHBOARD_CHOICE_ROUNDED'	=> 'Redondeado',
	'STUDIO_DASHBOARD_CHOICE_PHPBB'	=> 'Azul phpBB',
	'STUDIO_DASHBOARD_CHOICE_BLUE_LIGHT'	=> 'Azul claro',
	'STUDIO_DASHBOARD_CHOICE_DARK_BLUE'	=> 'Azul oscuro',
	'STUDIO_DASHBOARD_CHOICE_PURPLE_INDIGO'	=> 'Violeta a azul',
	'STUDIO_DASHBOARD_CHOICE_PURPLE_DARK'	=> 'Violeta oscuro',
	'STUDIO_DASHBOARD_CHOICE_PURPLE_ORANGE'	=> 'Violeta a naranja',
	'STUDIO_DASHBOARD_CHOICE_PINK_ORANGE'	=> 'Rosa a naranja',
	'STUDIO_DASHBOARD_CHOICE_ORANGE_LIGHT'	=> 'Naranja claro',
	'STUDIO_DASHBOARD_CHOICE_PINK_LIGHT'	=> 'Rosa claro',
	'STUDIO_DASHBOARD_CHOICE_GREEN_CYAN'	=> 'Verde a cian',
	'STUDIO_DASHBOARD_CHOICE_RED'	=> 'Rojo',
	'STUDIO_DASHBOARD_CHOICE_PURPLE'	=> 'Violeta',
	'STUDIO_DASHBOARD_CHOICE_VIOLET'	=> 'Rosa',
	'STUDIO_DASHBOARD_CHOICE_INDIGO'	=> 'Índigo',
	'STUDIO_DASHBOARD_CHOICE_CYAN'	=> 'Cian',
	'STUDIO_DASHBOARD_CHOICE_TEAL'	=> 'Verde azulado',
	'STUDIO_DASHBOARD_CHOICE_BLUE'	=> 'Azul',
	'STUDIO_DASHBOARD_CHOICE_ORANGE'	=> 'Naranja',
	'STUDIO_DASHBOARD_CHOICE_WHITE'	=> 'Blanco',
	'STUDIO_DASHBOARD_CHOICE_BLACK'	=> 'Negro',

	// ext.php: la extensión anterior sigue activada
	'STUDIO_DASHBOARD_OLD_INSTALLED'		=> 'La extensión anterior phpbbstudio/admindashboard está activada. Haga una copia de seguridad del foro y copie las notas de administración que quiera conservar. Desactive la extensión anterior, elimine sus datos por separado en la gestión de extensiones y active después esta extensión. Esta extensión no importa ni elimina los registros de la extensión anterior.',
]);
