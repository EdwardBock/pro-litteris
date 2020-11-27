'use strict';

import {registerPlugin} from "@wordpress/plugins"
import {PluginDocumentSettingPanel, PluginSidebarMoreMenuItem, PluginSidebar} from '@wordpress/edit-post'
import Plugin from './container/Plugin.js'
import { PanelBody } from "@wordpress/components";

export const DOCUMENT_PANEL_PLUGIN_ID = "pro-litteris-document-panel"

// ------------------------------------------
// extend documents panel
// ------------------------------------------
setTimeout(()=>{
    registerPlugin(DOCUMENT_PANEL_PLUGIN_ID, {
        render: () => <PluginDocumentSettingPanel
            title="ProLitteris"
            icon="media-text"
            priority="low"
        >
            <Plugin />
        </PluginDocumentSettingPanel>
    });
},300)

export const SIDEBAR_PLUGIN_ID = "pro-litteris-sidebar"

registerPlugin(SIDEBAR_PLUGIN_ID, {
    render: ()=>{
        return <>
            <PluginSidebarMoreMenuItem
                target={SIDEBAR_PLUGIN_ID}
                icon="media-text"
            >
                ProLitteris
            </PluginSidebarMoreMenuItem>
            <PluginSidebar
                name={SIDEBAR_PLUGIN_ID}
                icon="media-text"
                title="ProLitteris"
            >
                <PanelBody>
                    <Plugin />
                </PanelBody>
            </PluginSidebar>
        </>
    }
});

