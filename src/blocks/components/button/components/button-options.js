import React from 'react'; // eslint-disable-line no-unused-vars
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { URLInput } from '@wordpress/block-editor';
import { ColorPaletteCustom } from '@eightshift/frontend-libs/scripts/components';
import { SelectControl, TextControl, Icon, BaseControl } from '@wordpress/components';
import { getPaletteColors, icons } from '@eightshift/frontend-libs/scripts/editor';

export const buttonSizes = [
  { label: __('Default', 'eightshift-forms'), value: 'default' },
  { label: __('Big', 'eightshift-forms'), value: 'big' },
];

export const buttonSizeWidths = [
  { label: __('Default', 'eightshift-forms'), value: 'default' },
  { label: __('Block', 'eightshift-forms'), value: 'block' },
];

export const buttonColors = () => {
  const {
    primary,
    black,
  } = getPaletteColors();

  return [
    primary,
    black,
  ];
};

export const ButtonOptions = (props) => {
  const {
    button: {
      title,
      url,
      styleSize,
      styleColor,
      styleSizeWidth,
      id,
    },
    onChangeTitle,
    onChangeUrl,
    onChangeStyleSize,
    onChangeStyleColor,
    onChangeStyleSizeWidth,
    onChangeId,
  } = props;

  return (
    <Fragment>

      {onChangeTitle &&
        <TextControl
          label={__('Button Title', 'eightshift-forms')}
          value={title}
          onChange={onChangeTitle}
        />
      }

      {onChangeUrl &&
        <BaseControl label={__('Button Link', 'eightshift-forms')}>
          <URLInput
            value={url}
            onChange={onChangeUrl}
          />
        </BaseControl>
      }

      {onChangeStyleColor &&
        <ColorPaletteCustom
          label={
            <Fragment>
              <Icon icon={() => icons.color} />
              {__('Button Color', 'eightshift-forms')}
            </Fragment>
          }
          help={__('Change Button Background color.', 'eightshift-forms')}
          value={styleColor}
          colors={buttonColors()}
          onChange={onChangeStyleColor}
        />
      }

      {onChangeStyleSize &&
        <SelectControl
          label={__('Button Size', 'eightshift-forms')}
          value={styleSize}
          options={buttonSizes}
          onChange={onChangeStyleSize}
        />
      }

      {onChangeStyleSizeWidth &&
        <SelectControl
          label={__('Button Size Width', 'eightshift-forms')}
          value={styleSizeWidth}
          options={buttonSizeWidths}
          onChange={onChangeStyleSizeWidth}
        />
      }

      {onChangeId &&
        <TextControl
          label={__('Button ID', 'eightshift-forms')}
          value={id}
          onChange={onChangeId}
        />
      }

    </Fragment>
  );
};
