/* global grecaptcha */

import { State, ROUTES} from './state';
import { prefix, setStateWindow } from './state/init';
import { Utils } from './utilities';

/**
 * Captcha class.
 */
export class Captcha {
	constructor() {
		this.state = new State();
		this.utils = new Utils();

		// Set all public methods.
		this.publicMethods();
	}

	////////////////////////////////////////////////////////////////
	// Public methods
	////////////////////////////////////////////////////////////////

	/**
	 * Init all actions.
	 * 
	 * @returns {void}
	 */
	init() {
		// Load captcha on init.
		this.initCaptchaOnLoad();

		// Hide badge.
		this.initHideCaptchaBadge();
	}

	/**
	 * Initi captcha on load.
	 *
	 * @returns {void}
	 */
	initCaptchaOnLoad() {
		if (!this.state.getStateCaptchaIsUsed() || !this.state.getStateCaptchaLoadOnInit()) {
			return;
		}

		const actionName = this.state.getStateCaptchaInitAction();
		const siteKey = this.state.getStateCaptchaSiteKey();

		if (typeof grecaptcha === 'undefined') {
			return;
		}

		if (this.state.getStateCaptchaIsEnterprise()) {
			grecaptcha.enterprise.ready(async () => {
				await grecaptcha.enterprise.execute(siteKey, {action: actionName}).then((token) => {
					this.formSubmitCaptchaInvisible(token, true, actionName);
				});
			});
		} else {
			grecaptcha.ready(async () => {
				await grecaptcha.execute(siteKey, {action: actionName}).then((token) => {
					this.formSubmitCaptchaInvisible(token, false, actionName);
				});
			});
		}
	}

	/**
	 * Handle form submit and all logic in case we have captcha in place for init load.
	 * 
	 * @param {string} token Captcha token from api.
	 * @param {bool} isEnterprise Is enterprise setup.
	 * @param {string} action Action to use.
	 *
	 * @returns {void}
	 */
	formSubmitCaptchaInvisible(token, isEnterprise, action) {
		const formData = new FormData();

		formData.append('token', token);
		formData.append('isEnterprise', isEnterprise);
		formData.append('action', action);

		// Populate body data.
		const body = {
			method: 'POST',
			mode: 'same-origin',
			headers: {
				Accept: 'application/json',
			},
			body: formData,
			credentials: 'same-origin',
			redirect: 'follow',
			referrer: 'no-referrer',
		};

		fetch(this.state.getRestUrl(ROUTES.CAPTCHA), body)
		.then((response) => {
			this.utils.formSubmitErrorContentType(response, 'invisibleCaptcha', null);
			return response.json();
		})
		.then((response) => {
			this.utils.dispatchFormEvent(window, this.state.getStateEventsAfterCaptchaInit(), response);
		});
	}

	/**
	 * Hide captcha badge.
	 *
	 * @returns {void}
	 */
	initHideCaptchaBadge() {
		if (!this.state.getStateCaptchaIsUsed()) {
			return;
		}

		document.querySelector('body').setAttribute(this.state.getStateAttribute('hideCaptchaBadge'), this.state.getStateCaptchaHideBadge());
	}

	////////////////////////////////////////////////////////////////
	// Private methods - not shared to the public window object.
	////////////////////////////////////////////////////////////////

	/**
	 * Set all public methods.
	 * 
	 * @returns {void}
	 */
	publicMethods() {
		setStateWindow();

		if (window[prefix].captcha) {
			return;
		}

		window[prefix].captcha = {
			init: () => {
				this.init();
			},
			initCaptchaOnLoad: () => {
				this.initCaptchaOnLoad();
			},
			formSubmitCaptchaInvisible: (token, isEnterprise, action) => {
				this.formSubmitCaptchaInvisible(token, isEnterprise, action);
			},
			initHideCaptchaBadge: () => {
				this.initHideCaptchaBadge();
			},
		};
	}
}
