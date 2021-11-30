module.exports = {
  env: {
    node: true,
    es2021: true
  },
  extends: [
    'plugin:vue/essential',
    'eslint:recommended',
    'plugin:@intlify/vue-i18n/recommended',
    'prettier'
  ],
  parser: 'vue-eslint-parser',
  parserOptions: {
    ecmaVersion: 13,
    sourceType: 'module'
  },
  plugins: ['vue'],
  settings: {
    'vue-i18n': {
      localeDir: '/resources/assets/js/plugins/*.json',
      messageSyntaxVersion: '^9.0.0'
    }
  },
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'vue/multi-word-component-names': 'off',
    'vue/return-in-computed-property': 'off',
    'no-unused-vars': 'off',
    '@intlify/vue-i18n/no-dynamic-keys': 'error',
    '@intlify/vue-i18n/no-unused-keys': [
      'error',
      {
        extensions: ['.js', '.vue']
      }
    ]
  }
}
