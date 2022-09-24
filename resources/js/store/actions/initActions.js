import * as actionTypes from './actionTypes';
import axios from '../../axios-shop';
import * as ActionsBehavior from './index';

/**
 * Initialize supplies
 *
 * @param { Object } supplies supplies object
 *
 * @returns { Object }
 */
export const initSupplies = (supplies) => {
    return {
        type: actionTypes.INIT_SUPPLIES,
        supplies: supplies
    };
}

/**
 * Supplies async connecting to backend
 *
 * @returns { Object }
 */
export const initSuppliesDispatcher = () => {
    return dispatch => {
        axios.get('/api/franchisee/supplies/list')
            .then(response => {
                if (response.data) {
                    dispatch(initSupplies(response.data));
                }
            })
            .catch(error => {
                console.log(error);
            });
    };
};

/**
 * initialize categories
 *
 * @param {*} categories
 *
 * @returns { Object }
 */
export const initCategories = (categories) => {
    return {
        type: actionTypes.INIT_CATEGORIES,
        categories: categories
    };
}

/**
 * Categories async connecting to backend
 * @returns { Object }
 */
export const initCategoriesDispatcher = () => {
    return dispatch => {
        axios.get('/api/franchisee/categories/list')
            .then(response => {
                if (response.data) {
                    dispatch(initCategories(response.data));
                }
            })
            .catch(error => {
                console.log(error);
            });
    };
};

export const getAllBasketItemsInDB = () => {
    return dispatch => {
        axios.get('/api/franchisee/get-basket-items').then(response => {
            if (response.data.cart_items) {
                sessionStorage.setItem('cart_items', response.data.cart_items);
                if (response.data.cart_total) {
                    sessionStorage.setItem('cart_total', response.data.cart_total);
                }
                if (response.data.promo_code_setup) {
                    sessionStorage.setItem('promo_code_setup', response.data.promo_code_setup);
                }
                dispatch(initCartItems());
                dispatch(initCartTotalDispatcher());
            } else {
                sessionStorage.clear();
            }
        }).catch(error => {
            console.log(error);
        });
    }
}

/**
 * Cart items population based on session storage
 *
 * @returns { Object }
 */
export const initCartItems = () => {
    return {
        type: actionTypes.INIT_CART_ITEMS,
        cart_items: (sessionStorage.getItem('cart_items') != '' && sessionStorage.getItem('cart_items') != null) ? JSON.parse(sessionStorage.getItem('cart_items')) : false
    };
}

/**
 * Cart total population based on session storage
 * @param {*} cart_total
 * @returns { Object }
 */
export const initCartTotal = (cart_total) => {
    return {
        type: actionTypes.INIT_CART_TOTAL,
        cart_total: cart_total
    };
}

/**
 * Cart items population dispatcher based on session storage
 *
 * @returns { Object }
 */
export const initCartTotalDispatcher = () => {
    return dispatch => {
        const cartTotal = {
            total_items: 0,
            total_costs: 0,
            promo_code: '',
            promo_code_value: 0,
            total_to_be_paid: 0
        }

        const cartTotalStorageStr = sessionStorage.getItem('cart_total') ? JSON.parse(sessionStorage.getItem('cart_total')) : cartTotal;

        const cartStorageStr = sessionStorage.getItem('cart_items');
        let cartStorageObj = (cartStorageStr !== null) ? JSON.parse(cartStorageStr) : [];

        let total_quantity = 0;
        let total_cost = 0;
        for (let cartStorageStrIndex in cartStorageObj) {
            total_quantity += cartStorageObj[cartStorageStrIndex].quantity;
            total_cost += cartStorageObj[cartStorageStrIndex].total_cost;
        }

        cartTotalStorageStr.total_items = total_quantity
        cartTotalStorageStr.total_costs = total_cost
        cartTotalStorageStr.promo_code = ''
        cartTotalStorageStr.promo_code_value = 0
        cartTotalStorageStr.total_to_be_paid = total_cost

        sessionStorage.setItem('cart_total', JSON.stringify(cartTotalStorageStr));
        dispatch(initCartTotal(cartTotalStorageStr));
        dispatch(ActionsBehavior.save_basket_items_to_db());

        const promo_code_setup = (sessionStorage.getItem('promo_code_setup') != '') ? JSON.parse(sessionStorage.getItem('promo_code_setup')) : false;
        if (promo_code_setup) {
            dispatch(ActionsBehavior.onPromoCodeApplyDispatcher(promo_code_setup.promo_code));
        }
    }
}

/**
 * Promo code setup
 * @returns { Object }
 */
export const initPromoCode = () => {
    const defaultPromoCode = {
        promo_code: "",
        promo_code_value: 0,
        promo_code_coverage: []
    };
    return {
        type: actionTypes.INIT_PROMO_CODE,
        promo_code: (sessionStorage.getItem('promo_code_setup') !== '' && sessionStorage.getItem('promo_code_setup') !== null && sessionStorage.getItem('promo_code_setup') !== undefined) ? JSON.parse(sessionStorage.getItem('promo_code_setup')) : defaultPromoCode
    };
}


