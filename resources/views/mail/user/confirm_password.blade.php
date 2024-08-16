@if($token)

    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; padding: 20px; border-radius: 8px;">
                        <tr>
                            <td align="center" style="padding-bottom: 20px;">
                                <h1 style="font-size: 24px; color: #333;">Your Verification Token</h1>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 10px 0;">
                                <p style="font-size: 16px; color: #555;">
                                    Hello {{$user->name}},
                                </p>
                                <p style="font-size: 16px; color: #555;">
                                    Thank you for using our application! Please use the token below to confirm the password changing:
                                </p>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td align="center" style="padding: 20px 0;">
                                            <table cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 10px 20px; border-radius: 4px;">
                                                <tr>
                                                    <td align="center" style="color: #555; font-weight: bold;">
                                                        {{$token}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <p style="font-size: 16px; color: #555; padding: 20px 0 0 0;">
                                    If you did not request this token, please ignore this email.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 20px 0;">
                                <p style="font-size: 14px; color: #777;">
                                    Best regards,<br>
                                    The Laravel Solid Team
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

@else

    <div style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; height:100%; display: flex; align-items:center; justify-content:center;">
        <h1 style="font-weight: bold;">
            Data not found.
        </h1>
    </div>
    
@endif