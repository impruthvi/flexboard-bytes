<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Indian first names (male).
     *
     * @var list<string>
     */
    private array $maleFirstNames = [
        'Aarav', 'Vivaan', 'Aditya', 'Vihaan', 'Arjun', 'Sai', 'Reyansh', 'Ayaan',
        'Krishna', 'Ishaan', 'Shaurya', 'Atharva', 'Advik', 'Pranav', 'Advaith',
        'Aarush', 'Kabir', 'Ritvik', 'Anirudh', 'Dhruv', 'Arnav', 'Rohan', 'Karthik',
        'Rahul', 'Vikram', 'Nikhil', 'Akash', 'Raj', 'Sahil', 'Yash', 'Kunal',
        'Siddharth', 'Aryan', 'Dev', 'Harsh', 'Manish', 'Varun', 'Abhishek', 'Gaurav',
    ];

    /**
     * Indian first names (female).
     *
     * @var list<string>
     */
    private array $femaleFirstNames = [
        'Aadhya', 'Ananya', 'Aanya', 'Aarohi', 'Saanvi', 'Anika', 'Pari', 'Myra',
        'Sara', 'Ira', 'Diya', 'Prisha', 'Kavya', 'Navya', 'Kiara', 'Avni',
        'Anvi', 'Ishita', 'Shreya', 'Sneha', 'Pooja', 'Neha', 'Priya', 'Anjali',
        'Divya', 'Kriti', 'Nisha', 'Riya', 'Simran', 'Tanvi', 'Aditi', 'Megha',
        'Kritika', 'Khushi', 'Palak', 'Riddhi', 'Sanya', 'Trisha', 'Vidya', 'Zara',
    ];

    /**
     * Indian last names.
     *
     * @var list<string>
     */
    private array $lastNames = [
        'Sharma', 'Verma', 'Gupta', 'Singh', 'Kumar', 'Patel', 'Shah', 'Reddy',
        'Nair', 'Menon', 'Iyer', 'Rao', 'Pillai', 'Joshi', 'Desai', 'Mehta',
        'Kapoor', 'Malhotra', 'Chopra', 'Bhatia', 'Khanna', 'Saxena', 'Agarwal',
        'Mishra', 'Tiwari', 'Pandey', 'Dubey', 'Srivastava', 'Chauhan', 'Yadav',
        'Thakur', 'Patil', 'Kulkarni', 'Deshpande', 'Jain', 'Bansal', 'Goyal',
    ];

    /**
     * Indian-style usernames.
     *
     * @var list<string>
     */
    private array $usernamePrefixes = [
        'the', 'real', 'its', 'iamm', 'official', 'being', 'mr', 'ms', 'just',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isMale = fake()->boolean();
        $firstName = fake()->randomElement($isMale ? $this->maleFirstNames : $this->femaleFirstNames);
        $lastName = fake()->randomElement($this->lastNames);
        $fullName = $firstName.' '.$lastName;

        // Generate Indian-style username
        $usernameStyle = fake()->numberBetween(1, 5);
        $username = match ($usernameStyle) {
            1 => strtolower($firstName).fake()->numberBetween(1, 999),
            2 => strtolower($firstName.'_'.$lastName),
            3 => fake()->randomElement($this->usernamePrefixes).strtolower($firstName),
            4 => strtolower($firstName).fake()->randomElement(['_official', '_real', 'ji']),
            5 => strtolower(substr($firstName, 0, 1).$lastName).fake()->numberBetween(1, 99),
        };

        return [
            'name' => $fullName,
            'username' => $username.fake()->unique()->numberBetween(1, 9999),
            'email' => strtolower($firstName).'.'.strtolower($lastName).fake()->unique()->numberBetween(1, 999).'@'.fake()->randomElement(['gmail.com', 'outlook.com', 'yahoo.in', 'rediffmail.com']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'avatar' => null,
            'points' => fake()->numberBetween(0, 1000),
            'current_streak' => fake()->numberBetween(0, 30),
            'longest_streak' => fake()->numberBetween(0, 100),
            'last_flex_date' => fake()->optional()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
