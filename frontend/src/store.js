import { createStore, combineReducers } from 'redux';
import exampleReducer from './reducers/exampleReducer';

const rootReducer = combineReducers({
    example: exampleReducer,
});

const store = createStore(rootReducer);

export default store;
