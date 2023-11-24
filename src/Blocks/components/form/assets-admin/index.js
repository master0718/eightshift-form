/* global esFormsLocalization */

import domReady from '@wordpress/dom-ready';
import { Form } from './../assets/form';
import manifest from './../manifest.json';
import adminListingManifest from './../../admin-listing/manifest.json';
import { setStateInitial } from '../assets/state/init';
import { Utils } from '../assets/utils';

domReady(() => {
	// Global variable must be set for everything to work.
	if (typeof esFormsLocalization === 'undefined') {
		throw Error('Your project is missing global variable "esFormsLocalization" called from the enqueue script in the forms.');
	}

	// Bailout if no forms pages.
	if (esFormsLocalization.length === 0) {
		return;
	}

	// Set initial state.
	setStateInitial();

	// Load state helpers.
	const utils = new Utils();

	// Init form.
	new Form(utils).init();

	////////////////////////////////////////////////////////////////
	// Cache
	////////////////////////////////////////////////////////////////

	const selectorCache = `.${manifest.componentCacheJsClass}`;
	const elementsCache = document.querySelectorAll(selectorCache);

	if (elementsCache.length) {
		import('./cache').then(({ Cache }) => {
			new Cache({
				utils: utils,
				selector: selectorCache,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Migration
	////////////////////////////////////////////////////////////////

	const selectorMigration = `.${manifest.componentMigrationJsClass}`;
	const elementsMigration = document.querySelectorAll(selectorMigration);

	if (elementsMigration.length) {
		import('./migration').then(({ Migration }) => {
			new Migration({
				utils: utils,
				selector: selectorMigration,
				outputSelector: `.${manifest.componentMigrationJsClass}-output`,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Transfer
	////////////////////////////////////////////////////////////////

	const selectorTransfer = `.${manifest.componentTransferJsClass}`;
	const elementsTransfer = document.querySelectorAll(selectorTransfer);

	if (elementsTransfer.length) {
		import('./transfer').then(({ Transfer }) => {
			new Transfer({
				utils: utils,
				selector: selectorTransfer,
				itemSelector: `.${manifest.componentTransferJsClass}-item`,
				uploadSelector: `.${manifest.componentTransferJsClass}-upload`,
				overrideExistingSelector: `.${manifest.componentTransferJsClass}-existing`,
				uploadConfirmMsg: esFormsLocalization.uploadConfirmMsg,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Test api
	////////////////////////////////////////////////////////////////

	const selectorTestApi = `.${manifest.componentTestApiJsClass}`;
	const elementsTestApi = document.querySelectorAll(selectorTestApi);

	if (elementsTestApi.length) {
		import('./test-api').then(({ TestApi }) => {
			new TestApi({
				utils: utils,
				selector: selectorTestApi,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Filter
	////////////////////////////////////////////////////////////////

	const selectorFilter = `.${adminListingManifest.componentJsFilterClass}`;
	const elementsFilter = document.querySelector(selectorFilter);

	if (elementsFilter) {
		import('./filter').then(({ Filter }) => {
			new Filter({
				utils: utils,
				filterSelector: selectorFilter,
				itemSelector: `.${adminListingManifest.componentJsItemClass}`,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Bullk
	////////////////////////////////////////////////////////////////

	const selectorBulk = `.${adminListingManifest.componentJsBulkClass}`;
	const elementsBulk = document.querySelector(selectorBulk);

	if (elementsBulk) {
		import('./bulk').then(({ Bulk }) => {
			new Bulk({
				utils: utils,
				selector: selectorBulk,
				itemsSelector: `${selectorBulk}-items`,
				itemSelector: `.${adminListingManifest.componentJsItemClass}`,
				selectAllSelector: `.${adminListingManifest.componentJsSelectAllClass}`,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Locations
	////////////////////////////////////////////////////////////////

	const selectorLocations = `.${adminListingManifest.componentJsLocationsClass}`;
	const elementsLocations = document.querySelector(selectorLocations);

	if (elementsLocations) {
		import('./locations').then(({ Locations }) => {
			new Locations({
				utils: utils,
				selector: selectorLocations,
				itemSelector: `.${adminListingManifest.componentJsItemClass}`,
			}).init();
		});
	}

	////////////////////////////////////////////////////////////////
	// Manual import api
	////////////////////////////////////////////////////////////////

	const selectorManualImportApi = `.${manifest.componentManualImportApiJsClass}`;
	const elementsManualImportApi = document.querySelector(selectorManualImportApi);

	if (elementsManualImportApi) {
		import('./manual-import-api').then(({ ManualImportApi }) => {
			new ManualImportApi({
				utils: utils,
				selector: selectorManualImportApi,
				outputSelector: `.${manifest.componentManualImportApiJsClass}-output`,
				dataSelector: `.${manifest.componentManualImportApiJsClass}-data`,
			}).init();
		});
	}
});
