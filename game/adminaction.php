<?hh

require_once('request.php');
require_once('../common/teams.php');
require_once('../common/levels.php');
require_once('../common/attachments.php');
require_once('../common/links.php');
require_once('../common/logos.php');
require_once('../common/countries.php');
require_once('../common/sessions.php');
require_once('../common/utils.php');

sess_start();
sess_enforce_admin();

$filters = array(
  'POST' => array(
    'level_id'    => FILTER_VALIDATE_INT,
    'level_type'  => array(
      'filter'      => FILTER_VALIDATE_REGEXP,
      'options'     => array(
        'regexp'      => '/^[a-z]{4}$/'
      ),
    ),
    'team_id'     => FILTER_VALIDATE_INT,
    'session_id'  => FILTER_VALIDATE_INT,
    'cookie'      => FILTER_SANITIZE_STRING,
    'data'        => FILTER_UNSAFE_RAW,
    'name'        => FILTER_SANITIZE_STRING,
    'password'    => FILTER_UNSAFE_RAW,
    'admin'       => FILTER_VALIDATE_INT,
    'status'      => FILTER_VALIDATE_INT,
    'visible'     => FILTER_VALIDATE_INT,
    'all_type'    => array(
      'filter'      => FILTER_VALIDATE_REGEXP,
      'options'     => array(
        'regexp'      => '/^[a-z]{4}$/'
      ),
    ),
    'logo_id'     => FILTER_VALIDATE_INT,
    'logo'        => array(
      'filter'      => FILTER_VALIDATE_REGEXP,
      'options'     => array(
        'regexp'      => '/^[\w-]+$/'
      ),
    ),
    'entity_id'   => FILTER_VALIDATE_INT,
    'attachment_id' => FILTER_VALIDATE_INT,
    'filename'    => array(
      'filter'      => FILTER_VALIDATE_REGEXP,
      'options'     => array(
        'regexp'      => '/^[\w\-\.]+$/'
       ),
    ),
    'attachment_file' => FILTER_UNSAFE_RAW,
    'link_id'     => FILTER_VALIDATE_INT,
    'link'        => FILTER_VALIDATE_URL,
    'category_id' => FILTER_VALIDATE_INT,
    'category'    => FILTER_SANITIZE_STRING,
    'country_id'  => FILTER_VALIDATE_INT,
    'description' => FILTER_UNSAFE_RAW,
    'question'    => FILTER_UNSAFE_RAW,
    'flag'        => FILTER_UNSAFE_RAW,
    'answer'      => FILTER_UNSAFE_RAW,
    'hint'        => FILTER_UNSAFE_RAW,
    'points'      => FILTER_VALIDATE_INT,
    'bonus'       => FILTER_VALIDATE_INT,
    'bonus_dec'   => FILTER_VALIDATE_INT,
    'penalty'     => FILTER_VALIDATE_INT,
    'active'      => FILTER_VALIDATE_INT,
    'action'      => array(
      'filter'      => FILTER_VALIDATE_REGEXP,
      'options'     => array(
        'regexp'      => '/^[\w-]+$/'
      ),
    )
  )
);
$actions = array(
  'create_team',
  'create_quiz',
  'update_quiz',
  'create_flag',
  'update_flag',
  'create_base',
  'update_base',
  'update_team',
  'delete_team',
  'delete_level',
  'delete_all',
  'update_session',
  'delete_session',
  'toggle_status_level',
  'toggle_status_all',
  'toggle_status_team',
  'toggle_admin_team',
  'toggle_visible_team',
  'enable_country',
  'disable_country',
  'create_category',
  'delete_category',
  'enable_logo',
  'disable_logo',
  'create_attachment',
  'update_attachment',
  'delete_attachment',
  'create_link',
  'update_link',
  'delete_link',
  'reset_game'
);
$request = new Request($filters, $actions);
$request->processRequest();

switch ($request->action) {
  case 'none':
    admin_page();
    break;
  case 'create_quiz':
    $levels = new Levels();
    $levels->create_quiz_level(
      $request->parameters['question'],
      $request->parameters['answer'],
      $request->parameters['entity_id'],
      $request->parameters['points'],
      $request->parameters['bonus'],
      $request->parameters['bonus_dec'],
      $request->parameters['hint'],
      $request->parameters['penalty']
    );
    ok_response();
    break;
  case 'update_quiz':
    $levels = new Levels();
    $levels->update_quiz_level(
      $request->parameters['question'],
      $request->parameters['answer'],
      $request->parameters['entity_id'],
      $request->parameters['points'],
      $request->parameters['bonus'],
      $request->parameters['bonus_dec'],
      $request->parameters['hint'],
      $request->parameters['penalty'],
      $request->parameters['level_id']
    );
    ok_response();
    break;
  case 'create_flag':
    $levels = new Levels();
    $levels->create_flag_level(
      $request->parameters['description'],
      $request->parameters['flag'],
      $request->parameters['entity_id'],
      $request->parameters['category_id'],
      $request->parameters['points'],
      $request->parameters['bonus'],
      $request->parameters['bonus_dec'],
      $request->parameters['hint'],
      $request->parameters['penalty']
    );
    ok_response();
    break;
  case 'update_flag':
    $levels = new Levels();
    $levels->update_flag_level(
      $request->parameters['description'],
      $request->parameters['flag'],
      $request->parameters['entity_id'],
      $request->parameters['category_id'],
      $request->parameters['points'],
      $request->parameters['bonus'],
      $request->parameters['bonus_dec'],
      $request->parameters['hint'],
      $request->parameters['penalty'],
      $request->parameters['level_id']
    );
    ok_response();
    break;
  case 'create_base':
    $levels = new Levels();
    $levels->create_base_level(
      $request->parameters['description'],
      $request->parameters['entity_id'],
      $request->parameters['category_id'],
      $request->parameters['points'],
      $request->parameters['bonus'],
      $request->parameters['hint'],
      $request->parameters['penalty']
    );
    ok_response();
    break;
  case 'update_base':
    $levels = new Levels();
    $levels->update_base_level(
      $request->parameters['description'],
      $request->parameters['entity_id'],
      $request->parameters['category_id'],
      $request->parameters['points'],
      $request->parameters['bonus'],
      $request->parameters['hint'],
      $request->parameters['penalty'],
      $request->parameters['level_id']
    );
    ok_response();
    break;
  case 'delete_level':
    $levels = new Levels();
    $levels->delete_level(
      $request->parameters['level_id']
    );
    ok_response();
    break;
  case 'toggle_status_level':
    $levels = new Levels();
    $levels->toggle_status(
      $request->parameters['level_id'],
      $request->parameters['status']
    );
    ok_response();
    break;
  case 'toggle_status_all':
    if ($request->parameters['all_type'] === 'team') {
      $teams = new Teams();
      $teams->toggle_status_all(
        $request->parameters['status']
      );
      ok_response();
    } else {
      $levels = new Levels();
      $levels->toggle_status_all(
        $request->parameters['status'],
        $request->parameters['all_type']
      );
      ok_response();
    }
    break;
  case 'create_team':
    $teams = new Teams();
    $password_hash = $teams->generate_hash($request->parameters['password']);
    $teams->create_team(
      $request->parameters['name'],
      $password_hash,
      $request->parameters['logo']
    );
    ok_response();
    break;
  case 'update_team':
    $teams = new Teams();
    $teams->update_team(
      $request->parameters['name'],
      $request->parameters['logo'],
      $request->parameters['points'],
      $request->parameters['team_id']
    );
    if (strlen($request->parameters['password']) > 0) {
      $password_hash = $teams->generate_hash($request->parameters['password']);
      $teams->update_team_password(
        $password_hash,
        $request->parameters['team_id']
      );
    }
    ok_response();
    break;
  case 'toggle_admin_team':
    $teams = new Teams();
    $teams->toggle_admin(
      $request->parameters['team_id'],
      $request->parameters['admin']
    );
    ok_response();
    break;
  case 'toggle_status_team':
    $teams = new Teams();
    $teams->toggle_status(
      $request->parameters['team_id'],
      $request->parameters['status']
    );
    ok_response();
    break;
  case 'toggle_visible_team':
    $teams = new Teams();
    $teams->toggle_visible(
      $request->parameters['team_id'],
      $request->parameters['visible']
    );
    ok_response();
    break;
  case 'enable_logo':
    $logos = new Logos();
    $logos->toggle_status(
      $request->parameters['logo_id'],
      1
    );
    ok_response();
    break;
  case 'disable_logo':
    $logos = new Logos();
    $logos->toggle_status(
      $request->parameters['logo_id'],
      0
    );
    ok_response();
    break;
  case 'enable_country':
    $countries = new Countries();
    $countries->toggle_status(
      $request->parameters['country_id'],
      1
    );
    ok_response();
    break;
  case 'disable_country':
    $countries = new Countries();
    $countries->toggle_status(
      $request->parameters['country_id'],
      0
    );
    ok_response();
    break;
  case 'delete_team':
    $teams = new Teams();
    $teams->delete_team(
      $request->parameters['team_id']
    );
    ok_response();
    break;
  case 'update_session':
    sess_write(
      $request->parameters['cookie'],
      $request->parameters['data']
    );
    ok_response();
    break;
  case 'delete_session':
    sess_destroy(
      $request->parameters['cookie']
    );
    ok_response();
    break;
  case 'delete_category':
    $levels = new Levels();
    $levels->delete_category(
      $request->parameters['category_id']
    );
    ok_response();
    break;
  case 'create_category':
    $levels = new Levels();
    $levels->create_category(
      $request->parameters['category']
    );
    ok_response();
    break;
  case 'create_attachment':
    $attachments = new Attachments();
    $result = $attachments->create(
      'attachment_file',
      $request->parameters['filename'],
      $request->parameters['level_id']
    );
    if ($result) {
      ok_response();
    }
    break;
  case 'update_attachment':
    $attachments = new Attachments();
    $attachments->update(
      $request->parameters['filename'],
      $request->parameters['level_id']
    );
    ok_response();
    break;
  case 'delete_attachment':
    $attachments = new Attachments();
    $attachments->delete(
      $request->parameters['attachment_id']
    );
    ok_response();
    break;
  case 'create_link':
    $links = new Links();
    $result = $links->create(
      $request->parameters['link'],
      $request->parameters['level_id']
    );
    if ($result) {
      ok_response();
    }
    break;
  case 'update_link':
    $links = new Links();
    $links->update(
      $request->parameters['link'],
      $request->parameters['link_id']
    );
    ok_response();
    break;
  case 'delete_link':
    $links = new Links();
    $links->delete(
      $request->parameters['link_id']
    );
    ok_response();
    break;
  default:
    admin_page();
    break;
}
