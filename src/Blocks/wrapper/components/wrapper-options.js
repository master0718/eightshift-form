import React from 'react';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { PanelBody, TextControl, Icon, ToggleControl, SelectControl, RangeControl } from '@wordpress/components';
import { ColorPaletteCustom, Responsive, HelpModal } from '@eightshift/frontend-libs/scripts/components';
import { icons, ucfirst, getOptionColors } from '@eightshift/frontend-libs/scripts/editor';
import globalSettings from '../../manifest.json';
import manifest from './../manifest.json';

const { attributes: reset, options } = manifest;
const { globalVariables: defaults } = globalSettings;

export const WrapperOptions = ({ attributes, setAttributes }) => {
	const {
		wrapperUse,
		wrapperUseShowControl,
		wrapperUseSimple,
		wrapperUseSimpleShowControl,
		wrapperDisable,
		wrapperId,
		wrapperAnchorId,
		wrapperBackgroundColor,

		showWrapperId = true,
		showWrapperAnchorId = true,
		showWrapperBackgroundColor = true,
		showWrapperWidth = true,
		showWrapperOffset = true,
		showWrapperContainerWidth = true,
		showWrapperGutter = true,
		showWrapperSpacingTop = true,
		showWrapperSpacingBottom = true,
		showWrapperSpacingTopIn = true,
		showWrapperSpacingBottomIn = true,
		showWrapperDividerTop = true,
		showWrapperDividerBottom = true,
		showWrapperHide = true,
	} = attributes;

	const wrapperSpacingTop = [
		attributes.wrapperSpacingTopLarge,
		attributes.wrapperSpacingTopDesktop,
		attributes.wrapperSpacingTopTablet,
		attributes.wrapperSpacingTopMobile,
	];

	const wrapperSpacingBottom = [
		attributes.wrapperSpacingBottomLarge,
		attributes.wrapperSpacingBottomDesktop,
		attributes.wrapperSpacingBottomTablet,
		attributes.wrapperSpacingBottomMobile,
	];

	const wrapperSpacingTopIn = [
		attributes.wrapperSpacingTopInLarge,
		attributes.wrapperSpacingTopInDesktop,
		attributes.wrapperSpacingTopInTablet,
		attributes.wrapperSpacingTopInMobile,
	];

	const wrapperSpacingBottomIn = [
		attributes.wrapperSpacingBottomInLarge,
		attributes.wrapperSpacingBottomInDesktop,
		attributes.wrapperSpacingBottomInTablet,
		attributes.wrapperSpacingBottomInMobile,
	];

	const wrapperDividerTop = [
		attributes.wrapperDividerTopLarge,
		attributes.wrapperDividerTopDesktop,
		attributes.wrapperDividerTopTablet,
		attributes.wrapperDividerTopMobile,
	];

	const wrapperDividerBottom = [
		attributes.wrapperDividerBottomLarge,
		attributes.wrapperDividerBottomDesktop,
		attributes.wrapperDividerBottomTablet,
		attributes.wrapperDividerBottomMobile,
	];

	const wrapperHide = [
		attributes.wrapperHideLarge,
		attributes.wrapperHideDesktop,
		attributes.wrapperHideTablet,
		attributes.wrapperHideMobile,
	];

	const wrapperWidth = [
		attributes.wrapperWidthLarge,
		attributes.wrapperWidthDesktop,
		attributes.wrapperWidthTablet,
		attributes.wrapperWidthMobile,
	];

	const wrapperOffset = [
		attributes.wrapperOffsetLarge,
		attributes.wrapperOffsetDesktop,
		attributes.wrapperOffsetTablet,
		attributes.wrapperOffsetMobile,
	];

	const wrapperContainerWidth = [
		attributes.wrapperContainerWidthLarge,
		attributes.wrapperContainerWidthDesktop,
		attributes.wrapperContainerWidthTablet,
		attributes.wrapperContainerWidthMobile,
	];

	const wrapperGutter = [
		attributes.wrapperGutterLarge,
		attributes.wrapperGutterDesktop,
		attributes.wrapperGutterTablet,
		attributes.wrapperGutterMobile,
	];

	return (
		<>
			{!wrapperDisable &&
				<PanelBody title={__('Block Layout', 'EightshiftForms')} initialOpen={false} className="custom-highlighted-panel">

					<HelpModal />

					<br /><br />

					{wrapperUseShowControl &&
						<ToggleControl
							label={wrapperUse ? __('Wrapper Enabled', 'EightshiftForms') : __('Wrapper Disabled', 'EightshiftForms')}
							checked={wrapperUse}
							onChange={(value) => setAttributes({ wrapperUse: value })}
						/>
					}

					{(wrapperUse && wrapperUseSimpleShowControl) &&
						<ToggleControl
							label={wrapperUseSimple ? __('Wrapper Simple Enabled', 'EightshiftForms') : __('Wrapper Simple Disabled', 'EightshiftForms')}
							checked={wrapperUseSimple}
							onChange={(value) => setAttributes({ wrapperUseSimple: value })}
						/>
					}

					{wrapperUse &&
						<>
							<div className="custom-divider"></div>

							{showWrapperSpacingTop &&
								<Responsive
									label={
										<>
											<Icon icon={icons.spacingTop} />
											{__('Spacing Top', 'EightshiftForms')}
										</>
									}
								>
									{wrapperSpacingTop.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperSpacingTop${point}`;

										return (
											<Fragment key={index}>
												<RangeControl
													label={point}
													allowReset={true}
													value={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
													min={defaults.sectionSpacing.min}
													max={defaults.sectionSpacing.max}
													step={defaults.sectionSpacing.step}
													resetFallbackValue={reset[attr].default}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							{showWrapperSpacingBottom &&
								<Responsive
									label={
										<>
											<Icon icon={icons.spacingBottom} />
											{__('Spacing Bottom', 'EightshiftForms')}
										</>
									}
								>
									{wrapperSpacingBottom.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperSpacingBottom${point}`;

										return (
											<Fragment key={index}>
												<RangeControl
													label={point}
													allowReset={true}
													value={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
													min={defaults.sectionSpacing.min}
													max={defaults.sectionSpacing.max}
													step={defaults.sectionSpacing.step}
													resetFallbackValue={reset[attr].default}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							<div className="custom-divider"></div>

							{showWrapperSpacingTopIn &&
								<Responsive
									label={
										<>
											<Icon icon={icons.spacingTop} />
											{__('Spacing Top In', 'EightshiftForms')}
										</>
									}
								>
									{wrapperSpacingTopIn.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperSpacingTopIn${point}`;

										return (
											<Fragment key={index}>
												<RangeControl
													label={point}
													allowReset={true}
													value={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
													min={defaults.sectionInSpacing.min}
													max={defaults.sectionInSpacing.max}
													step={defaults.sectionInSpacing.step}
													resetFallbackValue={reset[attr].default}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							{showWrapperSpacingBottomIn &&
								<Responsive
									label={
										<>
											<Icon icon={icons.spacingBottom} />
											{__('Spacing Bottom In', 'EightshiftForms')}
										</>
									}
								>
									{wrapperSpacingBottomIn.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperSpacingBottomIn${point}`;

										return (
											<Fragment key={index}>
												<RangeControl
													label={point}
													allowReset={true}
													value={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
													min={defaults.sectionInSpacing.min}
													max={defaults.sectionInSpacing.max}
													step={defaults.sectionInSpacing.step}
													resetFallbackValue={reset[attr].default}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							<div className="custom-divider"></div>

							{showWrapperDividerTop &&
								<Responsive
									label={
										<>
											<Icon icon={icons.dividerTop} />
											{__('Divider Top', 'EightshiftForms')}
										</>
									}
								>
									{wrapperDividerTop.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperDividerTop${point}`;

										return (
											<Fragment key={index}>
												<ToggleControl
													label={point}
													checked={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							{showWrapperDividerBottom &&
								<Responsive
									label={
										<>
											<Icon icon={icons.dividerBottom} />
											{__('Divider Bottom', 'EightshiftForms')}
										</>
									}
								>
									{wrapperDividerBottom.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperDividerBottom${point}`;

										return (
											<Fragment key={index}>
												<ToggleControl
													label={point}
													checked={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							<div className="custom-divider"></div>

							{showWrapperHide &&
								<Responsive
									label={
										<>
											<Icon icon={icons.hide} />
											{__('Hide', 'EightshiftForms')}
										</>
									}
								>
									{wrapperHide.map((item, index) => {

										const point = ucfirst(options.breakpoints[index]);
										const attr = `wrapperHide${point}`;

										return (
											<Fragment key={index}>
												<ToggleControl
													label={point}
													checked={attributes[attr]}
													onChange={(value) => setAttributes({ [attr]: value })}
												/>
											</Fragment>
										);
									})}
								</Responsive>
							}

							<div className="custom-divider"></div>

							{!wrapperUseSimple &&
								<>
									{showWrapperWidth &&
										<Responsive
											label={
												<>
													<Icon icon={icons.width} />
													{__('Width', 'EightshiftForms')}
												</>
											}
										>
											{wrapperWidth.map((item, index) => {

												const point = ucfirst(options.breakpoints[index]);
												const attr = `wrapperWidth${point}`;

												return (
													<Fragment key={index}>
														<RangeControl
															label={point}
															allowReset={true}
															value={attributes[attr]}
															onChange={(value) => setAttributes({ [attr]: value })}
															min={options.widths.min}
															max={options.widths.max}
															step={options.widths.step}
															resetFallbackValue={reset[attr].default}
														/>
													</Fragment>
												);
											})}
										</Responsive>
									}

									{showWrapperOffset &&
										<Responsive
											label={
												<>
													<Icon icon={icons.offset} />
													{__('Offset', 'EightshiftForms')}
												</>
											}
										>
											{wrapperOffset.map((item, index) => {

												const point = ucfirst(options.breakpoints[index]);
												const attr = `wrapperOffset${point}`;

												return (
													<Fragment key={index}>
														<RangeControl
															label={point}
															allowReset={true}
															value={attributes[attr]}
															onChange={(value) => setAttributes({ [attr]: value })}
															min={options.widths.min}
															max={options.widths.max}
															step={options.widths.step}
															resetFallbackValue={reset[attr].default}
														/>
													</Fragment>
												);
											})}
										</Responsive>
									}

									{showWrapperContainerWidth &&
										<Responsive
											label={
												<>
													<Icon icon={icons.containerWidth} />
													{__('ContainerWidth', 'EightshiftForms')}
												</>
											}
										>
											{wrapperContainerWidth.map((item, index) => {

												const point = ucfirst(options.breakpoints[index]);
												const attr = `wrapperContainerWidth${point}`;

												return (
													<Fragment key={index}>
														<SelectControl
															label={point}
															options={options.containerWidths}
															value={attributes[attr]}
															onChange={(value) => setAttributes({ [attr]: value })}
														/>
													</Fragment>
												);
											})}
										</Responsive>
									}

									{showWrapperGutter &&
										<Responsive
											label={
												<>
													<Icon icon={icons.gutter} />
													{__('Gutter', 'EightshiftForms')}
												</>
											}
										>
											{wrapperGutter.map((item, index) => {

												const point = ucfirst(options.breakpoints[index]);
												const attr = `wrapperGutter${point}`;

												return (
													<Fragment key={index}>
														<SelectControl
															label={point}
															options={options.gutters}
															value={attributes[attr]}
															onChange={(value) => setAttributes({ [attr]: value })}
														/>
													</Fragment>
												);
											})}
										</Responsive>
									}

									{showWrapperBackgroundColor &&
										<ColorPaletteCustom
											label={
												<>
													<Icon icon={icons.color} />
													{__('Background Color', 'EightshiftForms')}
												</>
											}
											colors={getOptionColors(options.colors)}
											value={wrapperBackgroundColor}
											onChange={(value) => setAttributes({ wrapperBackgroundColor: value })}
										/>
									}
								</>
							}

							{showWrapperAnchorId &&
								<TextControl
									label={
										<>
											<Icon icon={icons.anchor} />
											{__('Block Anchor ID', 'EightshiftForms')}
										</>
									}
									value={wrapperAnchorId}
									onChange={(value) => setAttributes({ wrapperAnchorId: value })}
								/>
							}

							{showWrapperId &&
								<TextControl
									label={
										<>
											<Icon icon={icons.id} />
											{__('Block ID', 'EightshiftForms')}
										</>
									}
									value={wrapperId}
									onChange={(value) => setAttributes({ wrapperId: value })}
								/>
							}

						</>
					}
				</PanelBody>
			}
		</>
	);
};
