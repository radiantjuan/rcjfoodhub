import React  from 'react';
import UniContainer from '../../hoc/UniContainer/UniContainer';
import { Modal } from 'react-bootstrap';
import { connect } from 'react-redux';
import ItemInfoModal from '../../components/ItemInfoModal/ItemInfoModal';
import * as reduxActions from '../../store/actions/index';

const ItemModal = (props) => {
    return (
        <UniContainer>
            <Modal
                show={props.modal.show}
                backdrop="static"
                keyboard={false}
                animation={false}
                size="sm"
            >
                <ItemInfoModal
                    id={props.modal.product_state.id}
                    title={props.modal.product_state.supply_name}
                    imgUrl={props.modal.product_state.imgUrl}
                    price={props.modal.product_state.price}
                    category={props.modal.product_state.category}
                    gram={props.modal.product_state.gram}
                    onModalCloseEvent={() => props.onModalClose()}
                    onAddToCart={(product_id_quantity) => props.onAddToCartAction(product_id_quantity)}
                />
            </Modal>
        </UniContainer>
    )
}

const mapStateToProps = (state) => {
    return {
        modal: state.modal_state
    }
}

const mapActionsToProps = (dispatch) => {
    return {
        onModalClose: () => dispatch(reduxActions.toggleModal()),
        onAddToCartAction: (product_id_quantity) => dispatch(reduxActions.addToCartApi(product_id_quantity))
    }
}

export default connect(mapStateToProps, mapActionsToProps)(ItemModal);
