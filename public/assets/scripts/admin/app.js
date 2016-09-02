/**
 * app.js
 * This script is the main script on this project.
 */

// Start the main app logic.
requirejs(['jquery', 'defines', 'common'], function ($, def, common) {
  // Load the scripts in common.
  common.init();

  // Load page scripts.
  if (!pageId || $.inArray(pageId, config.noScriptPages) != -1) {
    return;
  }

  require(['scripts/admin/pages/' + pageId], function (subPage) {
    subPage.init();
  });
});