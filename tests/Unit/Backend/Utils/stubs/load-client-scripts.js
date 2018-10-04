/**
 *  This are the stubs data of window settings that will used in all vue components.
 */
let windowSettings = {
        "locale": "en",
        "appName": "SCIEMMS",
        "homePath": "/",
        "adminHomePath": "/admin",
        "adminPathName": "admin",
        "editorName": "Vue Unit Testing",
        "editorSiteUrl": "https://sciemms.frb.io",
        "locales": "en",
        "user": {
            "name": "Jan Daryl Galbo",
            "email": "jandarylgalbo@gmail.com",
            "active": true,
            "locale": "en",
            "timezone": "UTC",
            "permission": "access backend",
        },
        "permissions" : [
                "access backend"
        ],
        "isImpersonation": true,
        "usurperName": "superadmin",
        "blogEnabled": true,
        "blogPromoted": false,
        "multiLanguage": false,
        "formSettings": false,
        "formSubmissions": true,
        "metas": false,
        "redirection": false
}


/**
 * This are named routes that will use in the actions.js request.
 * To get the counter of posts draft, pending, and published.
 * And users active then form submissions.
 */
let windowRoute = {
    namedRoutes: {
        "admin.posts.draft.counter": {
            "uri": "posts/draft_counter",
            "methods": ["GET", "HEAD"],
            "domain": "thesis.test"
        },
        "admin.posts.pending.counter": {
            "uri": "posts/pending_counter",
            "methods": ["GET", "HEAD"],
            "domain": "thesis.test"
        },
        "admin.posts.published.counter": {
            "uri":"posts/published_counter",
            "methods": ["GET", "HEAD"],
            "domain": "thesis.test"
        },
        "admin.users.active.counter'": {
            "uri":"users/active_counter",
            "methods": ["GET", "HEAD"],
            "domain": "thesis.test"
        },
        "admin.form_submissions.counter": {
            "uri":"form_submissions/counter",
            "methods": ["GET", "HEAD"],
            "domain": "thesis.test"
        }
    },
    baseUrl: 'http://thesis.test/',
    baseProtocol: 'http',
    baseDomain: 'thesis.test',
    basePort: false,
    defaultParameters: {
        locale: "en"
    }
}

export { windowSettings, windowRoute }
