import React from 'react'; // eslint-disable-line no-unused-vars
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { PanelBody, TextControl, Dashicon, TabPanel, Icon, ToggleControl } from '@wordpress/components';
import { ColorPaletteCustom } from '@eightshift/frontend-libs/scripts/components';
import { icons } from '@eightshift/frontend-libs/scripts/editor';
import { WrapperResponsiveTabContent } from './wrapper-responsive-tab-content';

export const WrapperOptions = ({ attributes, actions }) => {
  const {
    hasWrapper,
    id,
    anchor,
    styleBackgroundColor,
    styleContentWidth,
    styleContentOffset,
    styleContainerWidth,
    styleContainerSpacing,
    styleSpacingTop,
    styleSpacingBottom,
    styleHideBlock,
  } = attributes;

  const {
    onChangeStyleContentWidthLarge,
    onChangeStyleContentOffsetLarge,
    onChangeStyleContainerWidthLarge,
    onChangeStyleContainerSpacingLarge,
    onChangeStyleSpacingTopLarge,
    onChangeStyleSpacingBottomLarge,
    onChangeStyleHideBlockLarge,

    onChangeStyleContentWidthDesktop,
    onChangeStyleContentOffsetDesktop,
    onChangeStyleContainerWidthDesktop,
    onChangeStyleContainerSpacingDesktop,
    onChangeStyleSpacingTopDesktop,
    onChangeStyleSpacingBottomDesktop,
    onChangeStyleHideBlockDesktop,

    onChangeStyleContentWidthTablet,
    onChangeStyleContentOffsetTablet,
    onChangeStyleContainerWidthTablet,
    onChangeStyleContainerSpacingTablet,
    onChangeStyleSpacingTopTablet,
    onChangeStyleSpacingBottomTablet,
    onChangeStyleHideBlockTablet,

    onChangeStyleContentWidthMobile,
    onChangeStyleContentOffsetMobile,
    onChangeStyleContainerWidthMobile,
    onChangeStyleContainerSpacingMobile,
    onChangeStyleSpacingTopMobile,
    onChangeStyleSpacingBottomMobile,
    onChangeStyleHideBlockMobile,

    onChangeStyleBackgroundColor,
    onChangeId,
    onChangeAnchor,
    onChangeHasWrapper,
  } = actions;

  const styleContentWidthObject = (typeof styleContentWidth === 'undefined') || styleContentWidth;
  const styleContentOffsetObject = (typeof styleContentOffset === 'undefined') || styleContentOffset;
  const styleContainerWidthObject = (typeof styleContainerWidth === 'undefined') || styleContainerWidth;
  const styleContainerSpacingObject = (typeof styleContainerSpacing === 'undefined') || styleContainerSpacing;
  const styleSpacingTopObject = (typeof styleSpacingTop === 'undefined') || styleSpacingTop;
  const styleSpacingBottomObject = (typeof styleSpacingBottom === 'undefined') || styleSpacingBottom;
  const styleHideBlockObject = (typeof styleHideBlock === 'undefined') || styleHideBlock;

  return (
    <Fragment>
      <PanelBody title={__('Block Responsive Layout', 'eightshift-forms')} initialOpen={false}>

        {onChangeHasWrapper &&
          <ToggleControl
            label={hasWrapper ? __('Wrapper Enabled', 'eightshift-forms') : __('Wrapper Disabled', 'eightshift-forms')}
            help={__('Toggle wrapper options on/off.', 'eightshift-forms')}
            checked={hasWrapper}
            onChange={onChangeHasWrapper}
          />
        }

        {hasWrapper &&
          <Fragment>
            <TabPanel
              className="custom-button-tabs"
              activeClass="components-button is-button is-primary"
              tabs={[
                {
                  name: 'large',
                  title: <Dashicon icon="desktop" />,
                  className: 'tab-large components-button is-button is-default custom-button-with-icon',
                },
                {
                  name: 'desktop',
                  title: <Dashicon icon="laptop" />,
                  className: 'tab-desktop components-button is-button is-default custom-button-with-icon',
                },
                {
                  name: 'tablet',
                  title: <Dashicon icon="tablet" />,
                  className: 'tab-tablet components-button is-button is-default custom-button-with-icon',
                },
                {
                  name: 'mobile',
                  title: <Dashicon icon="smartphone" />,
                  className: 'tab-mobile components-button is-button is-default custom-button-with-icon',
                },
              ]
              }
            >
              {(tab) => (
                <Fragment>
                  {tab.name === 'large' && (
                    <Fragment>
                      <br />
                      <strong className="notice-title">{__('Large Layout Options', 'eightshift-forms')}</strong>
                      <p>{__('This options will only control large screens options.', 'eightshift-forms')}</p>
                      <br />
                      <WrapperResponsiveTabContent
                        type={'large'}
                        contentWidth={styleContentWidthObject}
                        contentOffset={styleContentOffsetObject}
                        containerWidth={styleContainerWidthObject}
                        containerSpacing={styleContainerSpacingObject}
                        spacingTop={styleSpacingTopObject}
                        spacingBottom={styleSpacingBottomObject}
                        hideBlock={styleHideBlockObject}
                        onChangeContentWidth={onChangeStyleContentWidthLarge}
                        onChangeContentOffset={onChangeStyleContentOffsetLarge}
                        onChangeContainerWidth={onChangeStyleContainerWidthLarge}
                        onChangeContainerSpacing={onChangeStyleContainerSpacingLarge}
                        onChangeSpacingTop={onChangeStyleSpacingTopLarge}
                        onChangeSpacingBottom={onChangeStyleSpacingBottomLarge}
                        onChangeHideBlock={onChangeStyleHideBlockLarge}
                      />
                    </Fragment>
                  )}
                  {tab.name === 'desktop' && (
                    <Fragment>
                      <br />
                      <strong className="notice-title">{__('Desktop Layout Options', 'eightshift-forms')}</strong>
                      <p>{__('This options will only control desktop screens options. If nothing is set, parent options will be used.', 'eightshift-forms')}</p>
                      <br />
                      <WrapperResponsiveTabContent
                        type={'desktop'}
                        contentWidth={styleContentWidthObject}
                        contentOffset={styleContentOffsetObject}
                        containerWidth={styleContainerWidthObject}
                        containerSpacing={styleContainerSpacingObject}
                        spacingTop={styleSpacingTopObject}
                        spacingBottom={styleSpacingBottomObject}
                        hideBlock={styleHideBlockObject}
                        onChangeContentWidth={onChangeStyleContentWidthDesktop}
                        onChangeContentOffset={onChangeStyleContentOffsetDesktop}
                        onChangeContainerWidth={onChangeStyleContainerWidthDesktop}
                        onChangeContainerSpacing={onChangeStyleContainerSpacingDesktop}
                        onChangeSpacingTop={onChangeStyleSpacingTopDesktop}
                        onChangeSpacingBottom={onChangeStyleSpacingBottomDesktop}
                        onChangeHideBlock={onChangeStyleHideBlockDesktop}
                      />
                    </Fragment>
                  )}
                  {tab.name === 'tablet' && (
                    <Fragment>
                      <br />
                      <strong className="notice-title">{__('Tablet Layout Options', 'eightshift-forms')}</strong>
                      <p>{__('This options will only control tablet screens options. If nothing is set, parent options will be used.', 'eightshift-forms')}</p>
                      <br />
                      <WrapperResponsiveTabContent
                        type={'tablet'}
                        contentWidth={styleContentWidthObject}
                        contentOffset={styleContentOffsetObject}
                        containerWidth={styleContainerWidthObject}
                        containerSpacing={styleContainerSpacingObject}
                        spacingTop={styleSpacingTopObject}
                        spacingBottom={styleSpacingBottomObject}
                        hideBlock={styleHideBlockObject}
                        onChangeContentWidth={onChangeStyleContentWidthTablet}
                        onChangeContentOffset={onChangeStyleContentOffsetTablet}
                        onChangeContainerWidth={onChangeStyleContainerWidthTablet}
                        onChangeContainerSpacing={onChangeStyleContainerSpacingTablet}
                        onChangeSpacingTop={onChangeStyleSpacingTopTablet}
                        onChangeSpacingBottom={onChangeStyleSpacingBottomTablet}
                        onChangeHideBlock={onChangeStyleHideBlockTablet}
                      />
                    </Fragment>
                  )}
                  {tab.name === 'mobile' && (
                    <Fragment>
                      <br />
                      <strong className="notice-title ">{__('Mobile Layout Options', 'eightshift-forms')}</strong>
                      <p>{__('This options will only control mobile screens options. If nothing is set, parent options will be used.', 'eightshift-forms')}</p>
                      <br />
                      <WrapperResponsiveTabContent
                        type={'mobile'}
                        contentWidth={styleContentWidthObject}
                        contentOffset={styleContentOffsetObject}
                        containerWidth={styleContainerWidthObject}
                        containerSpacing={styleContainerSpacingObject}
                        spacingTop={styleSpacingTopObject}
                        spacingBottom={styleSpacingBottomObject}
                        hideBlock={styleHideBlockObject}
                        onChangeContentWidth={onChangeStyleContentWidthMobile}
                        onChangeContentOffset={onChangeStyleContentOffsetMobile}
                        onChangeContainerWidth={onChangeStyleContainerWidthMobile}
                        onChangeContainerSpacing={onChangeStyleContainerSpacingMobile}
                        onChangeSpacingTop={onChangeStyleSpacingTopMobile}
                        onChangeSpacingBottom={onChangeStyleSpacingBottomMobile}
                        onChangeHideBlock={onChangeStyleHideBlockMobile}
                      />
                    </Fragment>
                  )}
                </Fragment>
              )}
            </TabPanel>

            <hr />
            <strong className="notice-title">{__('Block Colors', 'eightshift-forms')}</strong>
            <br /><br />
            {onChangeStyleBackgroundColor &&
              <ColorPaletteCustom
                label={
                  <Fragment>
                    <Icon icon={() => icons.color} />
                    {__('Background Color', 'eightshift-forms')}
                  </Fragment>
                }
                help={__('Change Block Background color. Block spacing will be included in block background color.', 'eightshift-forms')}
                value={styleBackgroundColor}
                onChange={onChangeStyleBackgroundColor}
              />
            }

            <hr />
            <strong className="notice-title">{__('Block General', 'eightshift-forms')}</strong>
            <br /><br />
            {onChangeId &&
              <TextControl
                label={
                  <Fragment>
                    <Icon icon={() => icons.id} />
                    {__('Block ID', 'eightshift-forms')}
                  </Fragment>
                }
                help={__('Add Unique ID to the block.', 'eightshift-forms')}
                value={id}
                onChange={onChangeId}
              />
            }

            {onChangeAnchor &&
              <TextControl
                label={
                  <Fragment>
                    <Icon icon={() => icons.anchor} />
                    {__('Anchor', 'eightshift-forms')}
                  </Fragment>
                }
                help={__('Adds data-anchor attribut to the block that is used for scroll to option.', 'eightshift-forms')}
                value={anchor}
                onChange={onChangeAnchor}
              />
            }
          </Fragment>
        }
      </PanelBody>
    </Fragment>
  );
};
