/**
 * Categories JS
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */
import axios from 'axios';

export const AxiosInstance = () => {
    const auth_token = document.getElementById('auth_token');
    return axios.create({
        baseURL: '',
        headers: {
            get: {
                Authorization: 'Bearer ' + auth_token.value
            },
            post: {
                Authorization: 'Bearer ' + auth_token.value
            }
        }
    });
}
