/**
 * Core API & Fetch Helper
 */

export async function request(url, options = {}) {
    const defaultHeaders = {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...(options.headers || {})
        }
    };

    if (config.body && typeof config.body === 'object' && !(config.body instanceof FormData)) {
        config.body = JSON.stringify(config.body);
    }

    if (config.body instanceof FormData) {
        delete config.headers['Content-Type'];
    }

    try {
        const response = await fetch(url, config);
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : await response.text();

        if (!response.ok) {
            throw { status: response.status, data };
        }

        return data;
    } catch (error) {
        console.error(`[API Error] ${options.method || 'GET'} ${url}:`, error);
        throw error;
    }
}

export const api = {
    get: (url, options = {}) => request(url, { ...options, method: 'GET' }),
    post: (url, body, options = {}) => request(url, { ...options, method: 'POST', body }),
    put: (url, body, options = {}) => request(url, { ...options, method: 'PUT', body }),
    delete: (url, options = {}) => request(url, { ...options, method: 'DELETE' }),
};
