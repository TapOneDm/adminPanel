import React, { useEffect, useState } from 'react';

import './Featured.scss';
import itemPlaceholder from './item-placeholder.png';

export default function Featured() {
    const [items, setItems] = useState(null);

    async function setData() {
        let items = [
            {
                id: 0,
                name: 'Flotsam',
                image: itemPlaceholder,
                sales: '40k+ sales',
                revenue: '$1.4m revenue',
            },
            {
                id: 1,
                name: 'Siuuuuu',
                image: itemPlaceholder,
                sales: '40k+ sales',
                revenue: '$1.4m revenue',
            },
            {
                id: 2,
                name: 'Bluenose',
                image: itemPlaceholder,
                sales: '40k+ sales',
                revenue: '$1.4m revenue',
            },
        ];

        setItems(items);
    }

    useEffect(() => {
        setData();
    }, []);

    return (
        <div className="featured">
            <div className="featured-content">
                <div className="featured-content-block">
                    <div className="content-block-title">
                        Best-selling artists
                    </div>
                    <div className="content-block-items">
                        {items &&
                            (items as Array<any>).map((item) => (
                                <div key={item.id} className="block-item">
                                  <div className="block-item-data">
                                    <div className="block-item-image">
                                        <img
                                            src={item.image}
                                            alt="placeholder"
                                        />
                                    </div>
                                    <div className="block-item-name">{item.name}</div>
                                  </div>
                                    <div className="block-item-data">
                                        {item.sales}
                                    </div>
                                    <div className="block-item-data">
                                        {item.revenue}
                                    </div>
                                </div>
                            ))}
                    </div>
                    <button className="featured-content-block-button button">View all artists</button>
                </div>
                <div className="featured-content-block">
                    <div className="content-block-title">
                        Best-selling artists
                    </div>
                    <div className="content-block-items">
                        {items &&
                            (items as Array<any>).map((item) => (
                                <div key={item.id} className="block-item">
                                  <div className="block-item-data">
                                    <div className="block-item-image">
                                        <img
                                            src={item.image}
                                            alt="placeholder"
                                        />
                                    </div>
                                    <div className="block-item-name">{item.name}</div>
                                  </div>
                                    <div className="block-item-data">
                                        {item.sales}
                                    </div>
                                    <div className="block-item-data">
                                        {item.revenue}
                                    </div>
                                </div>
                            ))}
                    </div>
                    <button className="featured-content-block-button button">View all artists</button>
                </div>
                <div className="featured-content-block">
                    <div className="content-block-title">
                        Best-selling artists
                    </div>
                    <div className="content-block-items">
                        {items &&
                            (items as Array<any>).map((item) => (
                                <div key={item.id} className="block-item">
                                  <div className="block-item-data">
                                    <div className="block-item-image">
                                        <img
                                            src={item.image}
                                            alt="placeholder"
                                        />
                                    </div>
                                    <div className="block-item-name">{item.name}</div>
                                  </div>
                                    <div className="block-item-data">
                                        {item.sales}
                                    </div>
                                    <div className="block-item-data">
                                        {item.revenue}
                                    </div>
                                </div>
                            ))}
                    </div>
                    <button className="featured-content-block-button button">View all artists</button>
                </div>
            </div>
        </div>
    );
}
