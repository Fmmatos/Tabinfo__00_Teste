const { defineConfig } = require('@vue/cli-service')
const webpack = require('webpack')

module.exports = defineConfig({
    transpileDependencies: true
})

module.exports = {
    outputDir: '../..app/dist',
    publicPath: process.env.NODE_ENV === 'production' ? `__Z__DIR__Z__/` : `/`,

    configureWebpack: {
        plugins: [
            new webpack.DefinePlugin({
                __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
            }),
        ],
    },

}
