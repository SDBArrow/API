import React, { Component} from 'react';
import { Link } from "react-router-dom";

class Register extends Component{
    state = { 
        email: null,
        password: null,
        passwordcheck: null
     } 

    registeraccount(){
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title: 'React POST Request Example' })
        };
        fetch('https://reqres.in/api/posts', requestOptions)
            .then(response => response.json())
            .then(data => this.setState({ postId: data.id }));
    }
    render() { 
        return (
            <div className="w-96 bg-indigo-50 rounded-3xl py-20 select-none px-4">
                <div className="bg-logo1 w-full h-32 bg-no-repeat bg-center bg-contain " />
                <div className="bg-white h-20 mt-16  rounded-2xl py-3">
                    <label htmlFor="email" className="ml-4">Email 信箱</label>
                    <div className="flex mx-4">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input className="w-80 ml-4 mt-1 " type="email" name="email" placeholder="Username@gmail.com" />
                    </div>
                </div>
                <div className="bg-white h-20 mt-8  rounded-2xl py-3">
                    <label htmlFor="password" className="ml-4">密碼</label>
                    <div className="flex mx-4">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input className="w-80 ml-4 mt-1" type="password" name="password" placeholder="············" />
                    </div>
                </div>
                <div className="bg-white h-20 mt-8  rounded-2xl py-3">
                    <label htmlFor="passwordcheck" className="ml-4">再次輸入密碼</label>
                    <div className="flex mx-4">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input className="w-80 ml-4 mt-1" type="passwordcheck" name="passwordcheck" placeholder="············" />
                    </div>
                </div>
                <button className="h-10 w-full mt-8 bg-indigo-800 text-white rounded-3xl cursor-pointer hover:bg-sky-700 active:bg-indigo-800" onClick={this.registeraccount} >註冊</button>
                <div className=" grid gap-48 grid-cols-2 mt-8"><span className="w-8 cursor-pointer"><Link to="/">登入</Link></span><span className="cursor-pointer"><Link to="/Forget">忘記密碼?</Link></span></div>
            </div>
        );
    }
}
 
export default Register;