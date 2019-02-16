module.exports = {
  extends: [
    'eslint:recommended',
    'plugin:vue/recommended'
  ],
  rules: {
    'vue/no-unused-vars': 'error',
    'no-console': ["error", { allow: ["warn", "error"] }]
  }
}
