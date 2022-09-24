import React from 'react';
import {Switch} from 'react-router-dom';
import Layout from './hoc/Layout/Layout';
import './App.module.scss';
import {Route} from 'react-router-dom';

import Main from './containers/Main/Main';

const App = () => {
    return (
        <div>
            <Layout>
                <Switch>
                    <Route path="/" component={Main}/>
                </Switch>
            </Layout>
        </div>
    );
}

export default App;
