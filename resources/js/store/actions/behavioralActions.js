import * as actionTypes from './actionTypes';
import axios from '../../axios-shop';
import * as actionsInit from './index';

/**
 * Change category reducer
 * @param {*} category_value
 * @returns {Object}
 */
export const changeCategory = (category_value) => {
    return {
        type: actionTypes.CHANGE_CATEGORY,
        category_value: category_value
    };
};

/**
 * Change product franchise
 * @param {*} category_value
 * @returns {Object}
 */
export const productFranchiseChange = (product_franchise_change_value) => {
    return {
        type: actionTypes.CHANGE_FRANCHISE_CATEGORY,
        product_franchise_change_value: product_franchise_change_value
    };
};

/**
 * Clear category reducer
 *
 * @returns {Object}
 */
export const clearCategory = () => {
    return {
        type: actionTypes.CLEAR_CATEGORY
    };
};

/**
 * Clear category reducer
 *
 * @returns {Object}
 */
export const searchSupplyByName = (name) => {
    return {
        type: actionTypes.SEARCH_BY_SUPPLY_NAME,
        supply_name: name
    };
};


/**
 * Modal state reducer trigger
 * @param {*} productData
 * @returns
 */
export const toggleModalState = (productData) => {
    return {
        type: actionTypes.TOGGLE_MODAL,
        current_product_state: productData
    };
}

/**
 * Toggle modal dispatcher
 * @param {*} productData
 * @returns
 */
export const toggleModal = (productData) => {
    return dispatch => {
        dispatch(toggleModalState(productData));
    };
};


export const addToCartState = (productData) => {
    return {
        type: actionTypes.TOGGLE_MODAL,
        current_product_state: productData
    };
}

/**
 * getting quantity information and calculation in the backend
 * @param {int} product_id_quantity
 * @returns
 */
export const addToCartApi = (product_id_quantity) => {
    return dispatch => {
        axios.post('/api/franchisee/add-to-cart', product_id_quantity)
            .then(response => {
                const cartStorageStr = sessionStorage.getItem('cart_items');
                let cartStorageObj = (cartStorageStr !== null) ? JSON.parse(cartStorageStr) : [];

                //check if id existing
                const idExist = cartStorageObj.filter((val) => {
                    return val.id == response.data.id
                });

                if (idExist != '') {
                    cartStorageObj = cartStorageObj.map(($value) => {
                        if ($value.id == response.data.id) {
                            $value.quantity += response.data.quantity
                            $value.total_cost += response.data.total_cost
                        }
                        return $value;
                    });
                } else {
                    cartStorageObj.push(response.data);
                }

                sessionStorage.setItem('cart_items', JSON.stringify(cartStorageObj));
                dispatch(actionsInit.initCartItems());
                dispatch(actionsInit.initCartTotalDispatcher());
            })
            .catch(error => {
                console.log(error);
            });
    };
};

/**
 * updating basket items in the DB
 *
 * @return {void}
 */
export const save_basket_items_to_db = () => {
    return dispatch => {
        axios.post('/api/franchisee/update-basket-items', {
            cart_items: sessionStorage.getItem('cart_items'),
            cart_total: sessionStorage.getItem('cart_total'),
            promo_code_setup:sessionStorage.getItem('promo_code_setup')
        }).catch(error => {
            console.log(error);
        });
    }
}

/**
 * deletes item in cart
 *
 * @param id id of supply
 *
 * @return dispatch
 */
export const deleteItemInCartDispatcher = (id) => {
    return dispatch => {
        const cartStorageStr = sessionStorage.getItem('cart_items');
        let cartStorageObj = (cartStorageStr !== null) ? JSON.parse(cartStorageStr) : [];

        //delete
        const deleteted = cartStorageObj.filter((val) => {
            return val.id != id
        });

        sessionStorage.setItem('cart_items', JSON.stringify(deleteted));
        dispatch(actionsInit.initCartItems());
        dispatch(actionsInit.initCartTotalDispatcher());
    }
}

/**
 * Promocode dispatcher async from backend
 *
 * @param {string} promo_code promocode entered
 *
 * @returns dispatch
 */
export const onPromoCodeApplyDispatcher = (promo_code) => {
    const cartTotalStorageStr = sessionStorage.getItem('cart_total') ? JSON.parse(sessionStorage.getItem('cart_total')) : [];
    if (cartTotalStorageStr.length <= 0) {
        return false;
    }

    return dispatch => {
        if (promo_code == 'remove') {
            sessionStorage.removeItem('promo_code_setup');
            dispatch(save_basket_items_to_db());
            dispatch(actionsInit.initPromoCode());
            return false;
        }

        const data = {
            promo_code: promo_code,
            cart_items: (sessionStorage.getItem('cart_items') != '') ? JSON.parse(sessionStorage.getItem('cart_items')) : false
        }
        axios.post('/api/franchisee/apply-promo-code', data)
            .then(response => {
                let promo_code_setup = {
                    promo_code_id: response.data.id,
                    promo_code: response.data.name,
                    promo_code_total_discount: 0,
                    promo_code_coverage: [],
                    promo_code_exceptions: [],
                    previous_amount_to_be_paid: cartTotalStorageStr.total_to_be_paid,
                    new_amount_to_be_paid: cartTotalStorageStr.total_to_be_paid
                };
                switch (response.data.type) {
                    //TODO GET INDIVIDUAL PROMOCODE SETUP THEN SUBTRACT CART TOTAL
                    case 'Fixed Amount':
                        if (response.data.coverage_type == 'All Items') {
                            let previous_amount_to_be_paid = promo_code_setup.previous_amount_to_be_paid;
                            if (response.data.items_exception) {
                                let totalCostRemoval = 0;
                                for (let itemsExceptionIndex in response.data.items_exception) {
                                    totalCostRemoval += response.data.items_exception[itemsExceptionIndex].value;
                                    promo_code_setup.promo_code_exceptions.push({
                                        title: response.data.items_exception[itemsExceptionIndex].title
                                    })
                                }
                                previous_amount_to_be_paid -= totalCostRemoval;
                            }

                            if (previous_amount_to_be_paid == 0) {
                                promo_code_setup.promo_code_total_discount = 0;
                            } else {
                                promo_code_setup.promo_code_total_discount = response.data.promo_value;
                            }

                            promo_code_setup.promo_code_coverage.push({
                                title: response.data.coverage_type + ' in total',
                                previous_costs: promo_code_setup.previous_amount_to_be_paid,
                                previous_price: promo_code_setup.previous_amount_to_be_paid,
                                new_price: previous_amount_to_be_paid,
                                new_costs: previous_amount_to_be_paid,
                                total_discount: promo_code_setup.promo_code_total_discount,
                            });
                        } else {
                            const cart_items = (sessionStorage.getItem('cart_items') != '') ? JSON.parse(sessionStorage.getItem('cart_items')) : false;
                            const mapped_cart_items = cart_items.map((cart_item) => {
                                const filter = response.data.coverage.filter((cover) => {
                                    return cart_item.id == cover.id
                                });
                                if (filter.length !== 0) {
                                    promo_code_setup.promo_code_total_discount += filter[0].value * cart_item.quantity
                                    promo_code_setup.promo_code_coverage.push({
                                        title: 'x' + cart_item.quantity + ' ' + cart_item.title,
                                        previous_costs: cart_item.total_cost,
                                        previous_price: cart_item.price,
                                        new_price: cart_item.price - filter[0].value,
                                        new_costs: cart_item.total_cost - (filter[0].value * cart_item.quantity),
                                        total_discount: filter[0].value * cart_item.quantity,
                                    });
                                }
                                return cart_item;
                            });
                        }

                        promo_code_setup.new_amount_to_be_paid -= promo_code_setup.promo_code_total_discount
                        sessionStorage.setItem('promo_code_setup', JSON.stringify(promo_code_setup));
                        break;
                    case 'Percentage':
                        if (response.data.coverage_type == 'All Items') {
                            let previous_amount_to_be_paid = promo_code_setup.previous_amount_to_be_paid;
                            if (response.data.items_exception) {
                                let totalCostRemoval = 0;
                                for (let itemsExceptionIndex in response.data.items_exception) {
                                    totalCostRemoval += response.data.items_exception[itemsExceptionIndex].value;
                                    promo_code_setup.promo_code_exceptions.push({
                                        title: response.data.items_exception[itemsExceptionIndex].title
                                    })
                                }
                                previous_amount_to_be_paid -= totalCostRemoval;
                            }

                            promo_code_setup.promo_code_total_discount = previous_amount_to_be_paid * (response.data.promo_value / 100)
                            promo_code_setup.promo_code_coverage.push({
                                title: response.data.coverage_type + ' in total',
                                previous_costs: promo_code_setup.previous_amount_to_be_paid,
                                previous_price: promo_code_setup.previous_amount_to_be_paid,
                                new_price: promo_code_setup.new_amount_to_be_paid,
                                new_costs: promo_code_setup.new_amount_to_be_paid,
                                total_discount: promo_code_setup.promo_code_total_discount,
                            });
                        } else {
                            const cart_items = (sessionStorage.getItem('cart_items') != '') ? JSON.parse(sessionStorage.getItem('cart_items')) : false;
                            const mapped_cart_items = cart_items.map((cart_item) => {
                                const filter = response.data.coverage.filter((cover) => {
                                    return cart_item.id == cover.id
                                });
                                if (filter.length !== 0) {
                                    promo_code_setup.promo_code_total_discount += (cart_item.price * (filter[0].value / 100)) * cart_item.quantity
                                    promo_code_setup.promo_code_coverage.push({
                                        title: 'x' + cart_item.quantity + ' ' + cart_item.title,
                                        previous_costs: cart_item.total_cost,
                                        previous_price: cart_item.price,
                                        new_price: cart_item.price - (cart_item.price * (filter[0].value / 100)),
                                        new_costs: cart_item.total_cost - (cart_item.price * (filter[0].value / 100)) * cart_item.quantity,
                                        total_discount: (cart_item.price * (filter[0].value / 100)) * cart_item.quantity,
                                    });
                                }
                                return cart_item;
                            });
                        }

                        promo_code_setup.new_amount_to_be_paid -= promo_code_setup.promo_code_total_discount
                        sessionStorage.setItem('promo_code_setup', JSON.stringify(promo_code_setup));
                        break;
                    default:
                        break;
                }

                dispatch(save_basket_items_to_db());
                dispatch(actionsInit.initPromoCode());
            })
            .catch(error => {
                alert('Promo code doesn\'t exist or expired, please contact administrator');
                sessionStorage.removeItem('promo_code_setup');
                dispatch(actionsInit.initPromoCode());
                return false;
            });
    }
}

/**
 * Add to card behavior popup slide reducer event
 * @param {Boolean} popup_state
 * @returns
 */
export const addToCartMobileShow = (popup_state) => {
    return {
        type: actionTypes.ADD_TO_CART_MOBILE,
        popup_state: popup_state
    };
}
