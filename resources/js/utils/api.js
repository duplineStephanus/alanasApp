// Reusable Axios wrapper for POST/GET requests
import axios from 'axios';

export const postData = (url, data) => {
    return axios.post(url, data, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
};

export const getData = (url) => {
    return axios.get(url);
};
