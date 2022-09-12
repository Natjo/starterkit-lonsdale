/* eslint-disable */
import '@babel/polyfill';
import '@babel/plugin-syntax-dynamic-import';
import * as conditioner from 'conditioner-core/conditioner-core.min';
import thirdParts from './modules/thirdparts';

// analytics/pub/tracking files
thirdParts();

// conditionner
conditioner.addPlugin({
	// the plugin "monitor" hook
	// use in html : <div data-module="modules/component.js" data-context="@visible true"></div>
	monitor: {
		// the name of our monitor, not prefixed with "@"
		name: "visible",

		// the monitor factory method, this will create our monitor
		create: (context, element) => ({
			// current match state
			matches: false,

			// called by conditioner to start listening for changes
			addListener(change) {
				new IntersectionObserver((entries) => {
					// update the matches state
					this.matches = entries.pop().isIntersecting === context;

					// inform conditioner of the new state
					change();
				}).observe(element);
			},
		}),
	},

	// converts module aliases to paths
	moduleSetName: (name) => `./${name}.js`,

	// get the module constructor
	moduleGetConstructor: (module) => module.default,

	// override the import (this makes webpack bundle all the dynamically included files as well)
	// https://webpack.js.org/api/module-methods/#import-
	// - set to "eager" to create a single chunk for all modules
	// - set to "lazy" to create a separate chunk for each module
	moduleImport: (name) => import(/* webpackMode: "lazy" */ `${name}`),
});

conditioner.hydrate(document.documentElement);
