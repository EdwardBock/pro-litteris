const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config.js' );
const path = require('path')

module.exports = {
	...defaultConfig,
	devtool: 'source-map',
	entry: {
		'pro-litteris': path.resolve(__dirname, './src/scripts/gutenberg.js'),
	},
	output: {
		path: path.resolve(__dirname, './public/assets/gutenberg/.'),
	},
}
