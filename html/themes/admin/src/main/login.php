<div class="full-width-hero">
    <div class="hero-text">
        <form action="/admin/" method="post" class="form-login">
            
            <label>
                User Handle
                <input type="text" name="username"/>
            </label>
            
            <label>
                Passvort
                <input type="password" name="password"/>
            </label>
            
            <button type="submit" name="admin-login">
                Let me in.
            </button>
            
            <input type="hidden" name="handle_key" value="username"/>
            <input type="hidden" name="password_key" value="password"/>
        </form>
    </div>
</div>