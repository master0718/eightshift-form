import React from 'react';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { TextControl, ToggleControl } from '@wordpress/components';
import {
	icons,
	checkAttr,
	getAttrKey,
	IconLabel,
	IconToggle,
	props
} from '@eightshift/frontend-libs/scripts';
import { FieldOptions } from '../../../components/field/components/field-options';
import manifest from '../manifest.json';

export const SelectOptions = (attributes) => {
	const {
		setAttributes,
	} = attributes;

	const selectName = checkAttr('selectName', attributes, manifest);
	const selectIsDisabled = checkAttr('selectIsDisabled', attributes, manifest);
	const selectIsRequired = checkAttr('selectIsRequired', attributes, manifest);
	const selectTracking = checkAttr('selectTracking', attributes, manifest);

	const [showAdvanced, setShowAdvanced] = useState(false);

	return (
		<>
			<FieldOptions
				{...props('field', attributes)}
			/>

			<ToggleControl
				label={__('Show advanced options', 'eightshift-forms')}
				checked={showAdvanced}
				onChange={() => setShowAdvanced(!showAdvanced)}
			/>

			{showAdvanced &&
				<>
					<TextControl
						label={<IconLabel icon={icons.id} label={__('Name', 'eightshift-forms')} />}
						value={selectName}
						onChange={(value) => setAttributes({ [getAttrKey('selectName', attributes, manifest)]: value })}
					/>

					<TextControl
						label={<IconLabel icon={icons.id} label={__('Tacking Code', 'eightshift-forms')} />}
						value={selectTracking}
						onChange={(value) => setAttributes({ [getAttrKey('selectTracking', attributes, manifest)]: value })}
					/>

					<IconToggle
						icon={icons.play}
						label={__('Is Disabled', 'eightshift-forms')}
						checked={selectIsDisabled}
						onChange={(value) => setAttributes({ [getAttrKey('selectIsDisabled', attributes, manifest)]: value })}
					/>

					<IconToggle
						icon={icons.play}
						label={__('Is Required', 'eightshift-forms')}
						checked={selectIsRequired}
						onChange={(value) => setAttributes({ [getAttrKey('selectIsRequired', attributes, manifest)]: value })}
					/>
				</>
			}
		</>
	);
};
