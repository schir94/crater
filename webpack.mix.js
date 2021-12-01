const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')
const path = require('path')
const url = process.env.APP_URL.replace(/(^\w+:|^)\/\//, '')

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/assets/js')
    }
  }
})

mix
  .js('resources/assets/js/app.js', 'public/assets/js/')
  .vue({
    version: 2,
    extractVueStyles: true
  })
  .sass('resources/assets/sass/crater.scss', 'public/assets/css/')
  .options({
    postCss: [tailwindcss('./tailwind.config.js')]
  })
// .browserSync({
// watch: true,
// files: [
// 'public/assets/js/**/*',
// 'public/assets/css/**/*',
// 'public/**/*.+(html|php)',
// 'resources/views/**/*.php'
// ],
// proxy: 'crater.test'
// })

if (!mix.inProduction()) {
  mix
    .webpackConfig({
      devtool: 'source-map'
    })
    .sourceMaps()
    .options({
      hmrOptions: {
        host: process.env.SESSION_DOMAIN,
        port: 8080
      }
    })
} else {
  mix.version()
}
