import React, {useEffect, useState} from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import {Row, Col} from 'react-bootstrap';
import CardSupplies from '../../components/CardSupplies/CardSupplies';
import {connect} from 'react-redux';
import Categories from '../../components/Categories/Categories';
import ItemModal from '../ItemModal/ItemModal';
import * as reduxActions from '../../store/actions/index';
import SideBar from '../SideBar/SideBar';
import cssClasses from './Main.module.scss';
import {FaCartPlus, FaFilter, FaShoppingCart, FaTimes} from 'react-icons/fa';



const Main = (props) => {

    const [openFilter, setOpenFilter] = useState(false);
    const [openCart, setOpenCart] = useState(false);

    useEffect(() => {
        props.onSuppliesInit();
        props.onCategoriesInit();
    }, []);

    /**
     * Supply cards renderer
     *
     * @param {int} category_id
     * @param {Object} value
     * @param {Object} categories
     *
     * @returns JSX
     */
    const renderSupplyCards = (category_id, value, categories) => {
        // const categoryFilter = categories.filter((category_value) => {
        //     return category_value.id === category_id;
        // });
        //
        // let cardSupplies = ''

        return (
            <Col xs={6} md={4} lg={3} className="p-2" key={'col-' + value.id}>
                <CardSupplies
                    supply_id={value.id}
                    key={value.id}
                    supply_name={value.name}
                    category={value.categories_name}
                    price={value.price}
                    gram={value.gram}
                    imgUrl={value.img_url}
                    stock_count={value.stock_count}
                />
            </Col>
        )
    }

    /**
     * Open filter for mobile
     */
    const openFilterContainer = () => {
        if (!openFilter) {
            setOpenFilter(true)
        } else {
            setOpenFilter(false)
        }
    }

    /**
     * Opens cart
     * @returns
     */
    const openCartContainer = () => {
        if (!openCart) {
            setOpenCart(true);
        } else {
            setOpenCart(false);
        }
    }

    const categories = props.categories;
    const category_filter = props.category_filter;
    const search_by_name = props.search_by_name;
    let supplies_count = 0;
    const CardSupplyMap = props.supplies.map((value) => {
        const product_franchise = value.product_franchise;
        const category_id = value.categories_id;
        const lowerCaseSupplyName = value.name;
        if (lowerCaseSupplyName !== undefined && search_by_name !== '') {
            if (lowerCaseSupplyName.toLowerCase().search(search_by_name.toLowerCase()) >= 0) {
                return renderSupplyCards(category_id, value, categories)
            }
        } else if (category_filter.length !== 0 && props.product_franchise_filter === 'all') {
            if (category_filter === category_id) {
                supplies_count++;
                return renderSupplyCards(category_id, value, categories);
            }
        } else if (props.product_franchise_filter !== 'all') {
            if (product_franchise.length !== 0) {
                const filterProductFranchise = product_franchise.filter((val) => {
                    return val === props.product_franchise_filter;
                });
                if (filterProductFranchise.length > 0) {
                    if (category_filter.length !== 0) {
                        if (category_filter === category_id) {
                            return renderSupplyCards(category_id, value, categories);
                        }
                    } else {
                        return renderSupplyCards(category_id, value, categories)
                    }

                }
            }
        } else {
            supplies_count++;
            return renderSupplyCards(category_id, value, categories)
        }
        return false;
    });

    const shoppingCart = openCart ? <FaTimes/> : <FaShoppingCart/>;
    const cartItemsCount = props.cart_items.length;
    return (
        <UniContainer>
            <div className="row">
                <div className="d-block d-xl-none w-50 pl-3">
                    <div className={cssClasses.mobile_filter_btn + ' btn btn-sm btn-primary'} onClick={() => {
                        openFilterContainer();
                    }}>
                        <FaFilter/>
                    </div>
                </div>
                <div className="d-block d-xl-none text-right w-50 pr-3 position-relative">
                    <div
                        className={cssClasses.cart_count_indicator + (openCart ? ' d-none' : '')}>{cartItemsCount}</div>
                    <div
                        className={cssClasses.mobile_cart_btn + ' btn btn-sm btn-' + (openCart ? 'danger' : 'primary')}
                        onClick={() => {
                            openCartContainer();
                        }}>
                        {shoppingCart}
                    </div>
                </div>
            </div>
            <Row>
                <Col xs={12} xl={2}
                     className={cssClasses.mobile_filter_container + " m-0 p-0 mt-xl-2 px-xl-1 d-block"}
                     style={{left: openFilter ? 0 : -1500}}>
                    <Categories categories={props.categories}/>
                    <div className="d-block d-xl-none btn btn-danger btn-sm" onClick={() => {
                        openFilterContainer();
                    }}>
                        <FaTimes/>
                    </div>
                </Col>
                <Col xs={12} md={12} xl={7} className={openCart ? 'd-none' : "mt-2 px-1"}>
                    <div className={cssClasses.supply_product_container + ' container-fluid'}>
                        <Row>
                            {CardSupplyMap}
                        </Row>
                    </div>
                </Col>
                <Col xs={12} xl={3}
                     className={openCart ? 'mt-2 px-1 d-block col-12' : 'mt-2 px-1 d-none d-xl-block'}>
                    <SideBar/>
                </Col>
            </Row>
            <ItemModal/>
        </UniContainer>
    )
}

const mapStateToProps = state => {
    return {
        supplies: state.supplies,
        categories: state.categories,
        category_filter: state.category_filter,
        product_franchise_filter: state.product_franchise_filter,
        search_by_name: state.search_by_name,
        cart_items: state.cart
    };
}

const mapActionsToProps = dispatch => {
    return {
        onSuppliesInit: () => {
            dispatch(reduxActions.initSuppliesDispatcher());
        },
        onCategoriesInit: () => {
            dispatch(reduxActions.initCategoriesDispatcher());
        }
    };
}

export default connect(mapStateToProps, mapActionsToProps)(Main);
