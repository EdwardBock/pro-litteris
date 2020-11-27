import { __experimentalGetSettings, date } from '@wordpress/date';

export const dateFormat = (timestamp) =>{
    const settings = __experimentalGetSettings();
    return date( settings.formats.datetime , timestamp);
}
