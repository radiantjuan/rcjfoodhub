import React, { useState } from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import { Row, Col, Button, Form, Card } from 'react-bootstrap';
import { connect } from 'react-redux';
import * as reduxActions from '../../store/actions/index';
import cssClasses from "./Categories.module.scss"

const Categories = (props) => {
    const categoriesToggleButton = props.categories.map((value) => {
        let isChecked = props.category_filter === value.id
        return (
            // <ToggleButton key={'category-' + value.id} type="radio" name="categorySwitch" variant="primary" value={value.id}>{value.name}</ToggleButton>
            <Form.Check
                key={'categorySwitch' + value.id}
                type="switch"
                id={value.name}
                label={value.name}
                value={value.id}
                name={'categorySwitch'}
                onChange={(val) => props.onCategoryChangeDispatch(val)}
                checked={isChecked}
            />
        );
    });
    const franchiseTypeOptions = [
        {
            value: 'all',
            label: 'All'
        },
        {
            value: 'Franchise 1',
            label: 'Franchise 1'
        },
        {
            value: 'Franchise 2',
            label: 'Franchise 2'
        },

    ];
    const franchiseType = franchiseTypeOptions.map((type) => {
        let isChecked = props.product_franchise_filter === type.value
        return (
            <Form.Check
                key={'franchise_type_options_' + type.value}
                type="switch"
                id={type.label}
                label={type.label}
                value={type.value}
                name="franchise_type_options"
                onChange={(val) => props.onProductFranchiseChangeDispatch(val)}
                checked={isChecked}
            />
        )
    });

    const clearFilterButton = (props.category_filter.length !== 0) ? (<Button variant="danger" className="mb-2" onClick={() => props.onClearCategoryDispatch()}>Clear Filter</Button>) : '';
    return (
        <UniContainer>
            <Card className={cssClasses.categories_styles}>
                <Card.Body>
                    {clearFilterButton}
                    <Row className="mb-2">
                        <Col>
                            <Form.Control type="text" className="form-control-sm" placeholder="Search By Supply Name" value={props.search_by_name} onChange={(val) => props.onSearchSupply(val)} onKeyUp={(val) => props.onSearchSupply(val)} />
                        </Col>
                    </Row>
                    <hr />
                    <Row>
                        <Col>
                            <span>Franchise Type:</span>
                            {franchiseType}
                        </Col>
                    </Row>
                    <hr />
                    <Row>
                        <Col>
                            <span>Choose Category: </span>
                            {categoriesToggleButton}
                        </Col>
                    </Row>
                </Card.Body>
            </Card>
        </UniContainer>
    )
}

const mapStateToProps = state => {
    return {
        category_filter: state.category_filter,
        cart_count: state.cart_total.total_items,
        search_by_name: state.search_by_name,
        product_franchise_filter: state.product_franchise_filter
    };
}

const mapDispatchToProps = dispatch => {
    return {
        onCategoryChangeDispatch: (element) => {
            dispatch(reduxActions.changeCategory(parseInt(element.target.value)))
        },
        onProductFranchiseChangeDispatch: (element) => {
            dispatch(reduxActions.clearCategory());
            dispatch(reduxActions.productFranchiseChange(element.target.value));
        },
        onClearCategoryDispatch: () => dispatch(reduxActions.clearCategory()),
        onCartShowMobile: () => dispatch(reduxActions.addToCartMobileShow(true)),
        onSearchSupply: (element) => {
            dispatch(reduxActions.clearCategory()),
                dispatch(reduxActions.searchSupplyByName(element.target.value))
        }
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Categories);
