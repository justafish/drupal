module.exports = {
  '@tags': ['core'],
  'See if Tim Plunkett lives in America': (browser) => {
    browser
      .url('https://www.drupal.org/u/timplunkett')
      .assert.containsText('.field-name-field-country .field-item', 'United States')
      .drupalLogAndEnd({ onlyOnError: false });
  },
};
