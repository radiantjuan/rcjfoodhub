import * as actionTypes from '../actions/actionTypes';

const initState = {
    supplies: [],
    categories: [],
    category_filter: [],
    product_franchise_filter: 'all',
    search_by_name: '',
    modal_state: {
        show: false,
        product_state: []
    },
    cart: [],
    cart_total: {
        total_items: 0,
        total_costs: 0,
        promo_code: '',
        promo_code_value: 0,
        total_to_be_paid: 0
    },
    promo_code: {
        promo_code: '',
        promo_code_value: 0,
        promo_code_coverage: []
    },
    cart_popup_state: {
        show: false
    }
}


const reducer = (state = initState, action) => {
    switch (action.type) {
        case actionTypes.INIT_SUPPLIES:
            return {
                ...state,
                supplies: action.supplies
            }
        case actionTypes.INIT_CATEGORIES:
            return {
                ...state,
                categories: action.categories
            }
        case actionTypes.CHANGE_CATEGORY:
            return {
                ...state,
                category_filter: action.category_value,
                search_by_name: '',
            }
        case actionTypes.CHANGE_FRANCHISE_CATEGORY:
            return {
                ...state,
                product_franchise_filter: action.product_franchise_change_value,
                search_by_name: '',
            }
        case actionTypes.CLEAR_CATEGORY:
            return {
                ...state,
                category_filter: []
            }
        case actionTypes.SEARCH_BY_SUPPLY_NAME:
            return {
                ...state,
                search_by_name: action.supply_name,
            }
        case actionTypes.INIT_CART_ITEMS:
            if (action.cart_items) {
                return {
                    ...state,
                    cart: action.cart_items
                }
            } else {
                return {
                    ...state
                }
            }
        case actionTypes.INIT_CART_TOTAL:
            if (action.cart_total) {
                return {
                    ...state,
                    cart_total: action.cart_total
                }
            } else {
                return {
                    ...state
                }
            }
        case actionTypes.INIT_PROMO_CODE:
            return {
                ...state,
                promo_code: action.promo_code
            }
        case actionTypes.TOGGLE_MODAL:
            let modal_state = state.modal_state.show;
            if (modal_state === true) {
                modal_state = false;
            } else {
                modal_state = true
            }
            return {
                ...state,
                modal_state: {
                    ...state.modal_state,
                    show: modal_state,
                    product_state: (action.current_product_state) ? action.current_product_state : []
                }
            }
        case actionTypes.ADD_TO_CART_MOBILE:
            return {
                ...state,
                cart_popup_state: {
                    show: action.popup_state
                }
            }
        default:
            return state;
    }
};

export default reducer; ``
