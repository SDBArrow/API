import React, { Component } from 'react';
import { Link } from "react-router-dom";

class Forget extends Component {
    state = {  } 
    render() { 
        return (
            <div className="w-96 bg-indigo-50 rounded-3xl py-20 select-none px-4">
                <div className="bg-logo1 w-full h-32 bg-no-repeat bg-center bg-contain " />
                <div className="bg-white h-20 mt-16  rounded-2xl py-3">
                    <label htmlFor="email" className="ml-4">請輸入註冊的Email</label>
                    <div className="flex mx-4">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input className="w-80 ml-4 mt-1 " type="email" name="email" placeholder="Username@gmail.com" />
                    </div>
                </div>
                <button className="h-10 w-full mt-8 bg-indigo-800 text-white rounded-3xl">發送救援信件</button>
                <div className=" grid gap-72 grid-cols-2 mt-8"><span className="w-8 cursor-pointer"><Link to="/">登入</Link></span><span className="cursor-pointer"><Link to="/Register">註冊</Link></span></div>
            </div>
        );
    }
}
 
export default Forget;