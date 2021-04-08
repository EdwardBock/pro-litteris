import { __experimentalGetSettings, date } from '@wordpress/date';

export const dateFormat = (timestamp) =>{
    const settings = __experimentalGetSettings();
    return date( settings.formats.datetime , timestamp);
}

export const filterAuthors = (participants) => participants.filter(p => p.participation === "AUTHOR");
export const filterImageOriginators = (participants) => participants.filter(p => p.participation === "IMAGE_ORIGINATOR");